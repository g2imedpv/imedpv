<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\ORM\TableRegistry;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        $language = $this->request->getSession()->read('Language');
        $version = $this->request->getSession()->read('version');
        if($version == 2||$version ==null) $version = "";
        if($version == 3) $version = "_r3";
        I18n::setLocale($language);
        parent::initialize();
        TableRegistry::config('SdFields', ['table' => 'sd_fields'.$version]);
        TableRegistry::config('SdSections', ['table' => 'sd_sections'.$version]);
        TableRegistry::config('SdFieldValueLookUps', ['table' => 'sd_field_value_look_ups'.$version]);
        TableRegistry::config('SdSectionStructures', ['table' => 'sd_section_structures'.$version]);
        TableRegistry::config('SdSectionSummaries', ['table' => 'sd_section_summaries'.$version]);
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ],
                    'userModel' =>'SdUsers'
                ]
            ],
            'loginAction' => [
                'controller' => 'SdUsers',
                'action' => 'login'
            ],
             //use isAuthorized in Controllers
            'authorize' => ['Controller'],
             // If unauthorized, return them to page they were just on
            'unauthorizedRedirect' => $this->referer()
        ]);
        $this->Auth->allow(['setLanguage']);

        // Allow the display action so our PagesController
        // continues to work. Also enable the read only actions.

        // $this->Auth->allow(['display', 'view', 'index']);  // Comment this for disabling login function
        // $this->Auth->allow(); 

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }
    public function isAuthorized($user = null)
    {
        // Any registered user can access public functions
        if (!$this->request->getParam('prefix')) {
            return true;
        }

        // Only admins can access admin functions
        if ($this->request->getParam('prefix') === 'admin') {
            return (bool)($user['role'] === 'admin');
        }

        // Default deny
        return false;
    }

    public function debug($obj)
    {
        print "<pre>";
        print_r($obj);
        print "</pre>";
    }
}
