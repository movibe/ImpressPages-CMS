<?php
/**
 * @package   ImpressPages
 *
 *
 */
namespace Ip\Internal\Languages;



class AdminController extends \Ip\Grid\Controller
{
    public function init()
    {
        ipAddJs(ipFileUrl('Ip/Internal/Languages/assets/languages.js'));
    }


    public function index()
    {
        $response = parent::index() . $this->helperHtml();
        return $response ;
    }


    protected function helperHtml()
    {

        $helperData = array(
            'addForm' => $form = Helper::getAddForm()
        );
        return \Ip\View::create('view/helperHtml.php', $helperData)->render();
    }


    public function addLanguage()
    {
        ipRequest()->mustBePost();
        $data = ipRequest()->getPost();
        if (empty($data['code'])) {
            throw new \Ip\CoreException('Missing required parameter');
        }
        $code = $data['code'];
        $abbreviation = $code;
        $url = $code;

        $languages = Fixture::languageList();

        if (!empty($languages[$code])) {
            $language = $languages[$code];
            $title = $language['nativeName'];
        } else {
            $title = $code;
        }

        Service::addLanguage($title, $abbreviation, $code, $url, 1, Service::TEXT_DIRECTION_LTR);

        return new \Ip\Response\Json(array());
    }


    protected function config()
    {
        return array(
            'type' => 'table',
            'table' => 'language',
            'allowInsert' => false,
            'allowSearch' => false,
            'pageSize' => 3,
            'actions' => array(
                array(
                    'label' => __('Add', 'ipAdmin', false),
                    'class' => 'ipsCustomAdd'
                )
            ),
            'fields' => array(
                array(
                    'label' => __('Title', 'ipAdmin', false),
                    'field' => 'd_long',
                ),
                array(
                    'label' => __('Abbreviation', 'ipAdmin', false),
                    'field' => 'd_short',
                    'showInList' => false
                ),
                array(
                    'label' => __('Visible', 'ipAdmin', false),
                    'field' => 'visible'
                ),
                array(
                    'label' => __('Url', 'ipAdmin', false),
                    'field' => 'url',
                    'showInList' => false

                    /*
                    //TODOX add URL validator
                    'regExpression' => '/^([^\/\\\])+$/',
                    'regExpressionError' => __('Incorrect URL. You can\'t use slash in URL.', 'ipAdmin')
                    */
                ),
                array(
                    'label' => __('RFC 4646 code', 'ipAdmin', false),
                    'field' => 'code',
                    'showInList' => false
                ),
                array(
                    'label' => __('Text direction', 'ipAdmin', false),
                    'field' => 'text_direction',
                    'showInList' => false
                    //TODOX add select
                ),
            ),
            //'appendHtml' => Model
        );
    }

}