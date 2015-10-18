<?php
require_once  Yii::app()->basePath . "/extensions/simplehtmldom/simple_html_dom.php";
class SiteController extends Controller
{
    public function actions()
	{
		return array(
            'index' => array(
                'class' => 'SiteController',
                'view' => 'index'
            ),
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
                'view'=>'message'
			),
		);
	}
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
    /**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
        $allsite=file_get_html('http://dnepropetrovsk.dnp.olx.ua/nedvizhimost/arenda-kvartir/dolgosrochnaya-arenda-kvartir/' );
        $all_tr=$allsite->find('table[id="offers_table"]/table');
        foreach($all_tr as $index=>$article) {
                $title=$article->find('strong',0)->plaintext;
                $link=$article->find('a',0)->href;
                if(!empty($link) && $link!=="#")
                {
                    $add=file_get_html($link);
                    /*price*/
                    $price=$add->find('div[id=offeractions]/div[1]/div[1]/strong',0)->plaintext;
                    /*phone*/
                    $isclasses=$add->find('ul[id=contact_methods]/li',0)->class;
                    $isclasses_explode=explode(' ',$isclasses);
                    $phone_id='';
                    foreach($isclasses_explode as $index=>$lasses)
                    {
                        if(strpos($lasses,"'id':")!==false)
                        {
                            $lasses_explode=explode(':',$lasses);
                            $phone_id=str_replace("'",'',$lasses_explode[count($lasses_explode)-1]);
                            $phone_id=str_replace(",",'',$phone_id);
                        }
                    }

                    /*get contact - get to http://dnepropetrovsk.dnp.olx.ua/ajax/misc/contact/phone/{ID}/*/
                    $ch=curl_init();
//                    //GET запрос указывается в строке URL
                    curl_setopt($ch, CURLOPT_URL, 'http://dnepropetrovsk.dnp.olx.ua/ajax/misc/contact/phone/'.$phone_id.'/');
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Olx getter');
                    $data = curl_exec($ch);
                    curl_close($ch);
                    $phone=json_decode($data)->value;

                    /*description*/
                    $description=$add->find('div[id=textContent]/p[class=pding10 lheight20 large]',0)->plaintext;
                    /*author name */
                    $author=$add->find('div[id=offeractions]/span[class=block color-5 brkword xx-large]',0)->plaintext;
                    $search_by_link=Ads::model()->findByAttributes(array('link'=>$link));
                    if(!$search_by_link)
                    {
                        $ads=new Ads();
                        $ads->title=$title;
                        $ads->description=$description;
                        $ads->link=$link;
                        $ads->author=$author;
                        $ads->phone=$phone;
                        $ads->price=$price;
                        $ads->time_create=time();
                        $ads->read_status=0;
                        if($ads->save())
                        {
                            foreach($add->find('div[id=offerdescription]/img') as $index=>$im) {
                                $images=new Images();
                                $images->ads_id=$ads->id;
                                $images->link=$im->src;
                                $images->save();
                            }
                        }
                    }
                }

            }
    }
}