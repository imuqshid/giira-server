class places extends REST_Controller{

    public function __construct()
    {
        parent::__construct();

//        $this->load->helper('url');

        $this->load->model('placemodel');

    }
}
