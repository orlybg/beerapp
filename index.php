<?php
	require 'vendor/autoload.php';


	class BeerApp {
	    private $app;

	    public function __construct() {
	 		ORM::configure('mysql:host=127.0.0.1;dbname=beer_db');
			ORM::configure('username', 'root');
			ORM::configure('password', 'root');
			ORM::configure('logging', 'true');
			ORM::configure('return_result_sets', true);



	        $this->app = new \Slim\Slim();
			$this->app->get('/', array($this, 'home'));

			$this->app->get('/beers/new', array($this, 'beerForm'));
			$this->app->post('/beers/new', array($this, 'newBeer'));

			$this->app->get('/beers', array($this, 'beersAll'));
			$this->app->get('/beers.json', array($this, 'beersAllJson'));
			$this->app->get('/beers/:id', array($this, 'beerDetails'));


	
			
			$this->app->run();
	    }

	    public function home() {
	        echo 'Welcome to Beer App!';
//https://dl.dropboxusercontent.com/u/3654935/vendor.zip

	    }

	    public function beersAll() {
	    	$beers = $this->getAllBeers();
	    	$this->app->render('allbeers.html', array('beers' => $beers));
	    }

	    public function beersAllJson() {
	    	echo json_encode($this->getAllBeers(TRUE));
	    }

	    private function getAllBeers($asArray = FALSE) {
	    	if ($asArray)
	    		return $beers = ORM::for_table('beers')->find_array();
	    	else
	    		return $beers = ORM::for_table('beers')->find_many();
	    }

	    public function beerDetails($id) {
	    	$beer = ORM::for_table('beers')
	    		->join('manufacturers', array('manufacturers.id', '=', 'beers.manufacturer_id'))
	    		->where_equal('beers.id', $id)
	    		->find_one();

	    	print_r($beer);
	    	$this->app->render('beerdetails.html', array('beer' => $beer));
	    }

	    public function beerForm() {
	    	$this->app->render('beerform.html', array());
	    }

	    public function newBeer () {
	    	$beer = ORM::for_table('beers')->create();
			$beer->name = $_POST['name'];
			$beer->style = $_POST['style'];
			$beer->save();
	    }

	  
	}

	new BeerApp();