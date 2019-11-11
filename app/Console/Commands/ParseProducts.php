<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Puphpeteer\Resources\ElementHandle;

class ParseProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:products {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse products from Amazon';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        function floatvalue($val) {
            $val = str_replace("$", "", $val);
            $val = str_replace(",", ".", $val);
            $val = preg_replace('/\.(?=.*\.)/', '', $val);
            return floatval($val);
        }

        $url = $this->argument('url');

        $puppeteer = new Puppeteer;
        $browser = $puppeteer->launch();

        $page = $browser->newPage();
        $page->goto($url);

        $parsedCategories = $page->querySelectorAll('#zg_browseRoot ul ul li a');

        $links = [];
        $categories = [];

        $j = 0;

        foreach ($parsedCategories as $category) {
            $categories[] = $category->getProperty('innerText')->jsonValue();
            $links[] = $category->getProperty('href')->jsonValue();

            if (++$j == 5) break;
        }

        foreach ($links as $key => $link) {
            $puppeteer = new Puppeteer;
            $browser = $puppeteer->launch();

            $page = $browser->newPage();
            $page->goto($link);

            $products = [];

            $names = $page->querySelectorAll('.p13n-sc-truncated'); 
            $prices = $page->querySelectorAll('.p13n-sc-price'); 
            $images = $page->querySelectorAll('.a-section.a-spacing-small img'); 

            foreach($names as $name) {
                $products[]['name'] = $name->getProperty('innerText')->jsonValue();
            }

            

            for ($i = 0; $i < count($prices); $i++) {
                $products[$i]['price'] = floatvalue($prices[$i]->getProperty('innerText')->jsonValue());
            }

            for ($i = 0; $i < count($images); $i++) {
                $products[$i]['img'] = $images[$i]->getProperty('src')->jsonValue();
            }

            $browser->close();

            if(count($products) >= 6) {
                for ($i = 0; $i < 6; $i++) {
                    Product::add($products[$i], $categories[$key]);
                }
            } else {
                for ($i = 0; count($products); $i++) {
                    Product::add($products[$i], $categories[$key]);
                }
            }

            $this->info("Products from $categories[$key] has been upload");
        }

        $this->info('Parse Done!');
    }
}
