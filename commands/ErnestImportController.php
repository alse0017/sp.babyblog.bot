<?php
/**
 * Created by PhpStorm.
 * User: Alexeenko Sergey Aleksandrovich
 * Phone: +79231421947
 * Email: sergei_alekseenk@list.ru
 * Date: 19.01.2020
 * Time: 15:29
 */

namespace app\commands;

use app\components\babyblog\Babyblog;
use app\models\tables\SupplierProduct;
use app\models\tables\Supplier;
use yii\base\Exception;
use yii\console\Controller;
use yii\helpers\Console;
use Yii;

class ErnestImportController extends Controller
{
	public function actionIndex()
	{
	}

	public function actionInit()
	{
		$supplier = $this->getSupplier();
		$supplier->import_status = Supplier::IMPORT_STATUS_IMPORT;
		$supplier->import_data = $supplier->import_init_data;
		$supplier->import_id = time();

		if($supplier->save())
		{
			Yii::$app->db->createCommand()->delete(SupplierProduct::tableName(), [
				'supplier_id' => $supplier->id
			])->execute();

			$this->stdout("Инициализация нового импорта\n", Console::FG_GREEN, Console::BOLD);
			$this->stdout("Для запуска импорта запустите yii /ernest-import/run\n");
		}
		else $this->stderr('Ошибка инициализации нового импорта');
	}

	public function actionRun()
	{
		$this->execute();
	}

	protected function execute()
	{
		$babyblog = Babyblog::getInstance();
		if(!$babyblog->checkAuth())
		{
			if(!$babyblog->auth())
			{
				$this->stdout("--- error babyblog auth ---\n", Console::FG_RED, Console::BOLD);
				return;
			}
		}

		$supplier = $this->getSupplier();

		switch($supplier->import_status)
		{
			case Supplier::IMPORT_STATUS_IMPORT;
				$this->stdout("--- run import ---\n", Console::BOLD);
				return $this->executeImport();
				break;

			case Supplier::IMPORT_STATUS_AFTER_IMPORT;
				$this->stdout("--- run after import ---\n", Console::BOLD);
				return $this->executeAfterImport();
				break;
		}

		$this->stdout('end');
	}

	protected function executeImport()
	{
		require_once __DIR__.'/../libs/phpQuery.php';

		$supplier = $this->getSupplier();

		$checkImportStatus = function (){
			if(!$this->isImportStatus())
			{
				$this->stdout("--- detect not import status ---\n", Console::BOLD);
				$this->stdout("--- run next stage ---\n", Console::BOLD);
				return false;
			}
			return true;
		};

		if(!$checkImportStatus)
		{
			return $this->execute();
		}

		$importData = $supplier->import_data;
		$categoryLinks = (array)$importData['category_links'];
		if(!empty($categoryLinks))
		{
			$categoryLink = array_shift($categoryLinks);
			if(!empty($categoryLink['url']))
			{
				$page = 0;
				while(true)
				{
					if(!$checkImportStatus())
					{
						return $this->execute();
					}

					$get = ['sell_price' => ['min' => 0, 'max' => 20000,], 'sort_by' => 'sell_price', 'sort_order' => 'ASC', 'items_per_page' => 999,];
					if($page)
					{
						$get['page'] = $page;
					}
					$categoryUrl = $categoryLink['url']."?".http_build_query($get);

					$this->stdout("\n--- Parse category: $categoryUrl ---\n", Console::BOLD);

					$qHtml = \phpQuery::newDocumentHTML(file_get_contents($categoryUrl));

					$productUrls = [];

					if($qHtml->find('.view-content')->text())
					{
						foreach($qHtml->find('.view-content > div') as $qDiv)
						{
							$productUrl = trim(pq($qDiv)->find('a:first')->attr('href'));
							if($categoryUrl)
							{
								$productUrls[] = "http://ernest-opt.ru".$productUrl;
							}
						}
					}

					if($productUrls)
					{
						//$test = 0; # MH_DOWORK1 Убрать
						foreach($productUrls as $n => $productUrl)
						{
							if(!$checkImportStatus())
							{
								return $this->execute();
							}
							//$productUrl = 'http://ernest-opt.ru/product/vizitnica-portmone-nino-tacchini-9655';

							$this->stdout("\n--- Parse product ({$n}/".count($productUrls)."): ".$productUrl." ---\n", Console::BOLD);

							$productId = preg_replace('~(.+)-([\d]+)$~', '\\2', $productUrl);
							if(!$productId || $product = SupplierProduct::findByPrimary($supplier->id, $productId))
							{
								$this->stdout("Already added\n", Console::FG_BLUE);
								continue;
							}

							$qHtml = \phpQuery::newDocumentHTML(file_get_contents($productUrl));

							$product = new SupplierProduct();
							$product->supplier_id = $supplier->id;
							$product->product_id = $productId;
							$product->url = $productUrl;
							$product->babyblog_category_id = (int)$categoryLink['babyblog_id'];
							$product->name = trim($qHtml->find('h1')->text());
							$product->description = '';
							$product->pictures = $pictures = [];

							if($qHtml->find('.uc-cost')->size())
							{
								$product->price = floatval(str_replace(",", "", trim($qHtml->find('.uc-cost')->text())));
							}
							else
							{
								$product->price = floatval(str_replace(",", "", trim($qHtml->find('.uc-price')->text())));
							}

							foreach($qHtml->find('#thumblist a') as $a)
							{
								$src = preg_replace('~.*largeimage:\s?(\'|\")([^\?]+)\?.*~i', "\\2", pq($a)->attr("rel"));
								if($src)
								{
									$pictures[] = $src;
								}
							}
							if(empty($pictures))
							{
								$pictures[] = preg_replace(
									"~([^?]+)\?.*~i",
									"\\1",
									trim($qHtml->find('.jqzoom')->attr("href"))
								);
							}
							$product->pictures = $pictures;

							$props = [];

							foreach($qHtml->find('.product_blok_left') as $qDiv)
							{
								$prop = trim(pq($qDiv)->text());
								if($prop)
								{
									$props[] = $prop;

									if(stristr($prop, "Артикул:"))
									{
										$product->article = trim(str_ireplace("Артикул:", "", $prop), " \xC2\xA0");
									}
									elseif(stristr($prop, "Цвет:"))
									{
										$product->color = trim(str_ireplace("Цвет:", "", $prop), " \xC2\xA0");
									}
									elseif(stristr($prop, "Пол:") && stristr($categoryLink['babyblog_id'], "|"))
									{
										$product->babyblog_category_id = explode("|", $categoryLink['babyblog_id'])[stristr($prop, 'жен') ? 0:1];
									}
								}
							}

							if($props)
							{
								$product->description .= implode("", array_map(function ($item){
									return "<p>{$item}</p>";
								}, $props));
							}

							$text = trim($qHtml->find('.field-name-body')->text());
							if($text)
							{
								$product->description .= "<p> </p><p>{$text}</p>";
							}

							if($product->price <= 0 || ($supplier->max_price > 0 && $product->price > $supplier->max_price))
							{
								$this->stderr("Skipped: price conditions\n", Console::FG_BLUE);
								continue;
							}

							if($product->save())
							{
								$this->stdout("Supplier product: added\n", Console::FG_GREEN, Console::BOLD);

								try
								{
									$babyblogProduct = $product->createBabyblogProduct();
									if(!$babyblogProduct->save())
									{
										throw new Exception();
									}
									$this->stdout("Babyblog product: added", Console::FG_GREEN, Console::BOLD);

									$babyblogProduct->import();
									$this->stdout(" imported\n", Console::FG_GREEN, Console::BOLD);
								}
								catch(Exception $test)
								{
									$this->stdout("\rBabyblog product: error                                   \n", Console::FG_RED);
								}
							}
							else
							{
								$this->stdout("Supplier product: error\n", Console::FG_RED);
								$this->stdout($product->getErrorSummary(true), Console::FG_RED);
							}

							//if(++$test >= 2) break; # MH_DOWORK1 Убрать

							//$this->stdout($product->toArray());
							//die();
						}
					}

					if(!$productUrls || count($productUrls) < 999)
					{
						break;
					}

					++$page;
					//break; # MH_DOWORK1 Убрать
				}
			}
		}

		//$this->stdout($categoryLinks);

		$importData['category_links'] = $categoryLinks;
		$supplier->import_data = $importData;
		if(empty($categoryLinks))
		{
			$this->stdout("--- set status after import ---\n", Console::BOLD);
			$supplier->import_status = Supplier::IMPORT_STATUS_AFTER_IMPORT;
		}
		$supplier->save(false);

		//return $this->stdout('end');
		return $this->isImportStatus() ? $this->executeImport():$this->execute();
	}

	protected function executeAfterImport()
	{
		$supplier = $this->getSupplier();

		$this->stdout("--- Деактивация товаров, которых небыло в выгрузке ---\n", Console::BOLD);
		$count = $supplier->deactivateBabyblogProducts();
		echo $this->stdout("Найдено товаров: {$count}\n", Console::FG_GREEN);

		$this->stdout("--- Активация ранее деактивированных товаров, которые снова появились ---\n", Console::BOLD);
		$count = $supplier->activateBabyblogProducts();
		echo $this->stdout("Найдено товаров: {$count}\n", Console::FG_GREEN);

		$this->stdout("--- set status stop ---\n", Console::BOLD);
		$supplier->import_status = Supplier::IMPORT_STATUS_STOP;
		$supplier->save(false);

		return $this->execute();
	}

	protected function getSupplier()
	{
		return Supplier::findOne(Supplier::SUPPLIER_ERNEST_ID);
	}

	protected function isImportStatus()
	{
		return $this->getSupplier()->import_status === Supplier::IMPORT_STATUS_IMPORT;
	}

	/**
	 * @param array|string $data
	 */
	public function stdout($data)
	{
		$args = [];
		if($this->isColorEnabled())
		{
			$args = func_get_args();
			array_shift($args);
		}

		parent::stdout(is_array($data) ? print_r($data, 1):$data, ...$args);
	}
}