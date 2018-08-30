<?php
/**
 * @copyright Copyright (c) 2018, Felix Nüsse
 * 30.08.18 - 15:38
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

namespace OCA\Unsplash\Services;


use OCP\Files\IAppData;
use OCP\Http\Client\IClientService;
use OCP\IConfig;

class ImageUpdateService
{
    /**
     * @var IAppData
     */
    private $appData;

    /**
     * @var IClientService
     */
    protected $clients;

    /**
     * @var \OCP\Http\Client\IClient
     */
    protected $client;

    /**
     * @var IConfig
     */
    private $config;

    /**
     * ImageUpdateService constructor.
     *
     * @param string|null $userId
     * @param             $appName
     * @param IConfig $config
     * @param IAppData $appData
     * @param IClientService $clients
     */
    public function __construct($userId, $appName, IConfig $config, IAppData $appData, IClientService $clients) {
        $this->config = $config;
        $this->userId = $userId;
        if($this->config->getSystemValue('maintenance', false)) {
            $this->userId = null;
        }
        $this->appName = $appName;
        $this->appData = $appData;
        $this->clients = $clients;
        $this->client = $this->clients->newClient();
    }

    /**
     * Update the Image when the timer has run out.
     * @throws \OCP\Files\NotPermittedException
     */
    public function resolveUnsplashWhenNessessary(){
        if(true){
            $this->resolveUnsplash();
        }
    }

    /**
     * Update the Image immediately.
     * @throws \OCP\Files\NotPermittedException
     */
    public function resolveUnsplash(){
        $curlurl=$this->getUrlByCurl("https://source.unsplash.com/random/featured/?nature");
        $this->getImageByUrl($curlurl);
    }

    /**
     * Stores the image from $url to the appdatafolder of unsplash to unsplashImageFolder/image.jpg
     *
     * @param $url
     * @throws \OCP\Files\NotPermittedException
     */
    public function getImageByUrl($url){

        $simplefolder = $this->appData->newFolder("unsplashImageFolder");
        $simplefile = $simplefolder->newFile("image.jpg");
        $simplefile->putContent($this->client->get($url)->getBody());

    }


    /**
     * Resolve Unsplash-API-Url and return the resulting Imageurl.
     * Returns NOLOCATION if there was an error.
     *
     * @param $url
     * @return string
     */
    public function getUrlByCurl($url){

        $curl_instance = curl_init();

        curl_setopt($curl_instance, CURLOPT_URL, $url);
        curl_setopt($curl_instance, CURLOPT_HEADER, 1);
        curl_setopt($curl_instance, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl_instance);
        // close curl resource to free up system resources
        curl_close($curl_instance);

        $result_list = explode("\n", $result);
        foreach($result_list as $line){
            if(strpos($line, 'Location') === 0){
                return str_replace("Location: ","" , $line);
            }
        }

        return "NOLOCATION";
    }

}