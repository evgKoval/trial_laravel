<?php

namespace App\Console\Commands;

use App\Product;
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
        $url = $this->argument('url');

        $segments = explode('/', $url);

        if(strpos($segments[5], '?')) {
            $segments[5] = strstr($segments[5], '?', true);
        }

        $category = $segments[5];

        $puppeteer = new Puppeteer;
        $browser = $puppeteer->launch();

        $page = $browser->newPage();
        $page->goto($url);

        $products = [];

        $names = $page->querySelectorAll('.p13n-sc-truncated'); 
        $prices = $page->querySelectorAll('.p13n-sc-price'); 
        $images = $page->querySelectorAll('.a-section.a-spacing-small img'); 

        foreach($names as $name) {
            $products[]['name'] = $name->getProperty('innerText')->jsonValue();
        }

        for ($i = 0; $i < count($products); $i++) {
            $products[$i]['price'] = $prices[$i]->getProperty('innerText')->jsonValue();
        }

        for ($i = 0; $i < count($images); $i++) {
            $products[$i]['img'] = $images[$i]->getProperty('src')->jsonValue();
        }

        $browser->close();

        foreach ($products as $product) {
            Product::add($product, $category);
        }

        $this->info('Products has been upload');
    }
}
