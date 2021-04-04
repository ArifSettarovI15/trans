<?php


class Taxi
{
    /**
     * @var MainClass
     */
    var $registry;
    /**
     * @var DatabaseClass
     */
    var $db;

    /**
     * @var TaxiClasses
     */
    var $classes;

    /**
     * @var TaxiCars
     */
    var $cars;
    /**
     * @var TaxiCallBacks
     */
    var $callbacks;
    /**
     * @var TaxiCities
     */
    var $cities;

    /**
     * @var TaxiRoutes
     */
    var $routes;

    /**
     * @var TaxiPrices
     */
    var $prices;
    /**
     * @var TaxiReviews
     */
    var $reviews;
    /**
     * @var TaxiCarsClasses
     */
    var $cars_classes;
    /**
     * @var TaxiReviewsRating
     */
    var $reviews_rating;

    /**
     * @var TaxiMailing
     */
    var $mailing;

    /**
     * @var TaxiArticles
     */
    var $articles;
    /**
     * @var TaxiArticlesCategories
     */
    var $articles_categories;
    /**
     * @var TaxiOrders
     */
    var $orders;
    /**
     * @var TaxiRentCars
     */
    var $rent_cars;
    /**
     * @var TelegramBot
     */
    var $telegram_bot;
    /**
     * @var TelegramBot
     */
    var $drivers;
    /**
     * @var TaxiTelegramHistory
     */
    var $telegram_history;

    var $rent_requests;
	var $partners;
	var $smsru;

	public function __construct(&$registry)
    {

        $this->registry =& $registry;
        $this->db =& $this->registry->db;

        require_once 'classes.php';
        require_once 'cars.php';
        require_once 'cars_classes.php';
        require_once 'callback.php';
        require_once 'cities.php';
        require_once 'routes.php';
        require_once 'prices.php';
        require_once 'reviews.php';
        require_once 'reviews_rating.php';
        require_once 'mailing.php';
        require_once 'articles.php';
        require_once 'articles_categories.php';
        require_once 'partners.php';
        require_once 'orders.php';
        require_once 'rent.php';
        require_once 'telegram_bot.php';
        require_once 'order_life_time.php';
        require_once 'drivers.php';
        require_once 'smsru.php';
        require_once 'rent_requests.php';
        require_once 'telegram_history.php';

        $this->classes=new TaxiClasses($registry);
        $this->cities=new TaxiCities($registry);
        $this->cars=new TaxiCars($registry);
        $this->routes=new TaxiRoutes($registry);
        $this->prices=new TaxiPrices($registry);
        $this->cars_classes=new TaxiCarsClasses($registry);
        $this->callbacks=new TaxiCallBacks($registry);
        $this->reviews=new TaxiReviews($registry);
        $this->reviews_rating=new TaxiReviewsRating($registry);
        $this->mailing = new TaxiMailing($registry);
        $this->articles = new TaxiArticles($registry);
        $this->articles_categories = new TaxiArticlesCategories($registry);
        $this->partners = new TaxiPartners($registry);
        $this->orders = new TaxiOrders($registry);
        $this->rent_cars = new TaxiRentCars($registry);
        $this->telegram_bot = new TelegramBot($registry);
        $this->orders_lifetime = new TaxiLife($registry);
        $this->drivers = new TaxiDrivers($registry);
        $this->smsru = new TaxiSMSRu();
        $this->rent_requests = new TaxiRentRequests($registry);
        $this->telegram_history = new TaxiTelegramHistory($registry);
    }
}
