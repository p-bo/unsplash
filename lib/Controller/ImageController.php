<?php
/**
 * @copyright Copyright (c) 2018, Felix Nüsse
 * 29.08.18 - 17:59
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

use OCA\Unsplash\Services\ImageUpdateService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\FileDisplayResponse;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Files\IAppData;
use OCP\Files\NotPermittedException;
use OCP\Http\Client\IClientService;
use OCP\ILogger;
use OCP\IRequest;


class ImageController extends Controller
{

	/**
	 * @var IAppData
	 */
	protected $appData;

    /**
     * @var IClientService
     */
	protected $clients;

    /**
     * @var \OCP\Http\Client\IClient
     */
	protected $client;

    /**
     * @var ILogger
     */
	protected $logger;

    /**
     * @var ImageUpdateService
     */
	protected $ImageUpdate;

    /**
     * ImageController constructor.
     *
     * @param string $appName
     * @param IRequest $request
     * @param IAppData $appData
     * @param IClientService $clients
     * @param ILogger $logger
     * @param ImageUpdateService $ImageUpdate
     */
	public function __construct($appName, IRequest $request, IAppData $appData, IClientService $clients, ILogger $logger, ImageUpdateService $ImageUpdate) {
		parent::__construct($appName, $request);

		$this->appData = $appData;
		$this->logger = $logger;
		$this->clients = $clients;
		$this->client = $this->clients->newClient();
        $this->ImageUpdate = $ImageUpdate;

	}

    /**
     * Triggers the update of an Image, if the Image needs to be updated.
     *
     * @NoCSRFRequired
     * @PublicPage
     */
    public function checkImageUpdateController(){

        return new TemplateResponse($this->appName, "");

    }

    /**
     * Returns the Currently Cached Image. Triggers an update if no Image was chached.
     *
     * @NoCSRFRequired
     * @PublicPage
     * @throws \OCP\Files\NotFoundException
     */
    public function getCurentBackgroundImage(){

        $appfolder = $this->appData->getFolder("unsplashImageFolder");
        if(!$appfolder->fileExists("image.jpg")){

            try {
                $this->ImageUpdate->resolveUnsplash();
            } catch (NotPermittedException $e) {
                $this->logger->error($e);
            }
        }

        $file =$appfolder->getFile("image.jpg");

        $response = new FileDisplayResponse( $file);
		$response->addHeader('Content-Type', $file->getMimeType());
        return $response;

    }

}