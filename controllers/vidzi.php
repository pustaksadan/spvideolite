<?php
/**
 * Copyright 2015 SongPhi
 * 
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy
 * of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

 /**
* 
*/
class SPVIDEOLITE_CTRL_Vidzi extends OW_ActionController
{

	public function embed(array $params) {
		$videoId = $params['videoId'];

        $this->assign('staticUrl',OW::getPluginManager()->getPlugin( 'spvideolite' )->getStaticUrl());
        $this->assign('staticJsUrl',OW::getPluginManager()->getPlugin( 'spvideolite' )->getStaticJsUrl());
        $this->assign('staticCssUrl',OW::getPluginManager()->getPlugin( 'spvideolite' )->getStaticCssUrl());
        $this->assign('videoId',$videoId);

        $this->setTemplate( OW::getPluginManager()->getPlugin( 'spvideolite' )->getCtrlViewDir() . 'vidzi_embed.html' );
        die($this->render());
	}

}

