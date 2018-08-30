<?php
/**
 * @copyright Copyright (c) 2018, Felix Nüsse
 * 30.08.18 - 14:31
 * @author Felix Nüsse <felix.nuesse@t-online.de>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Unsplash\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\IRequest;
use OCP\IURLGenerator;

class CssController extends Controller
{
    /**
     * @var IURLGenerator urlGenertor
     */
    private $urlGenerator;

    /**
     * ImageController constructor.
     *
     * @param                 $appName
     * @param IRequest $request
     * @param IURLGenerator $urlGenerator
     */
    public function __construct($appName, IRequest $request, IURLGenerator $urlGenerator)
    {
        parent::__construct($appName, $request);
        $this->urlGenerator=$urlGenerator;
    }


    /**
     * Returns the CSS for the loginpage
     *
     * @NoCSRFRequired
     * @PublicPage
     */
    public function getLoginCss()
    {
        $url=$this->urlGenerator->linkToRouteAbsolute('unsplash.Image.getCurentBackgroundImage');

        $content =
"#body-login {
background-color    : #777 !important;
background-image    : url('$url') !important;
background-position : center;
background-repeat   : no-repeat;
background-size     : cover;
}
#body-login input.primary,
#body-login button.primary,
#body-login #alternative-logins li a {
    background-color : rgba(0, 0, 0, .5) !important;
}";

        return $this->CssResponse($content);
    }

    /**
     * Returns the CSS for the Headerbar
     *
     * @NoCSRFRequired
     * @PublicPage
     */
    public function getHeaderCss()
    {
        $url=$this->urlGenerator->linkToRouteAbsolute('unsplash.Image.getCurentBackgroundImage');
        $content =
"#body-user #header,
#body-settings #header,
#body-public #header {
    background-color    : #777 !important;
    background-image    : url('$url') !important;
    background-position : center;
    background-repeat   : no-repeat;
    background-size     : cover;
}

   /* Header icons get drop shadow for visibility */
#header .logo.logo-icon,
#appmenu svg,
.notifications .notifications-button img,

#contactsmenu .icon-contacts,
#expand .avatardiv-shown img,
#expand .expandDisplayName {
    /* Modified .icon-shadow with modified \$color-box-shadow */
    filter : drop-shadow(0 0 3px rgba(50, 50, 50, .5)) !important;
}
/* Header icons get higher opacity for visibility */
#appmenu li a {
    opacity : .8;
}
.searchbox input[type='search']:focus,
.searchbox input[type='search']:active,
.searchbox input[type='search']:valid {
    background-color : rgba(0, 0, 0, .5) !important;
    border           : none !important

}";

        return $this->CssResponse($content);
    }


    /**
     * Generates the proper Css Response
     *
     * @param $content
     * @return DataDisplayResponse
     */
    private function CssResponse($content){
        $response = new DataDisplayResponse($content);
        $response->addHeader('Content-Disposition', 'inline');
        $response->addHeader('Content-Type', 'text/css');
        return $response;
    }
}