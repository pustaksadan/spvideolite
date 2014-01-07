<?php

/**
 *
 */
class SPVIDEO_CLASS_EventHandler {

	private $route = null;

	/**
	 * 
	 */
	private function getRoute() {
		if ( !$this->route ) {
			try {
				$this->route = OW::getRouter()->route();
			} catch (Exception $e) {
				$this->route = false;
			}
		}
		return $this->route;
	}

	/**
	 * 
	 */
	private function isRoute( $controller, $action = null ) {
		if ($this->getRoute() == false)
			return;
		if ( $this->route["controller"] == $controller ) {
			if ( $action==null || $this->route["action"] == $action  ) {
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 */
	public function replaceVideoAddView( $event ) {
		if ( !$this->isRoute( 'VIDEO_CTRL_Add' ) )
			return;
		if ( OW::getRequest()->isPost() )
			return;
		if ( OW::getRequest()->isAjax() )
			return;
		if( !OW::getUser()->isAuthenticated() )
			return;

		$doc = OW::getDocument();

		// add scripts
		OW::getDocument()->addScript(
			OW::getPluginManager()
			->getPlugin( 'spvideo' )
			->getStaticUrl().'js/base64.js'
		);
		OW::getDocument()->addScript(
			OW::getPluginManager()
			->getPlugin( 'spvideo' )
			->getStaticUrl().'js/spvideo.js'
		);
		OW::getDocument()->addScript(
			OW::getPluginManager()
			->getPlugin( 'spvideo' )
			->getStaticUrl().'js/jquery.easing.min.js'
		);

		// add stylesheets
		OW::getDocument()->addStyleSheet(
			OW::getPluginManager()
			->getPlugin( 'spvideo' )
			->getStaticCssUrl().'spvideo.css'
		);

		// inject & modify the adding form
		$embedForm = $doc->getBody();
		$matches = array();
		preg_match_all( "/<form.*<\/form>/is", $embedForm, $matches );
		if (count($matches[0]) > 0)
			$embedForm = $matches[0][0];
		$spVideoCtrl = new SPVIDEO_CTRL_Add();
		$spVideoCtrl->setTemplate( OW::getPluginManager()->getPlugin( 'spvideo' )->getCtrlViewDir() . 'add_index.html' );
		$spVideoCtrl->setEmbedForm( $embedForm );

		$spVideoCtrl->index();

		// commit body changes
		$doc->setBody( $spVideoCtrl->render() );

	}

	/**
	 *
	 */
	function on_add_console_item( BASE_CLASS_EventCollector $event ) {
		$event->add( array( 'label' => 'My Videos', 'url' => OW_Router::getInstance()->urlForRoute( 'spvideo.my_video' ) ) );
	}

	/**
   * ============= TWEAKS =============
   */

  /**
   * 
   */
  public static function showLessVideoDescription(BASE_CLASS_EventCollector $event) {
  	OW::getDocument()->addOnloadScript("
  		if ($('.ow_video_description').height()>60) {
        $('.ow_video_description').attr('origheight',$('.ow_video_description').height());
  			$('.ow_video_description').css({'max-height':'60px','overflow':'hidden'});
				$('<div id=\"video-show-less\" class=\"ow_small ow_txtcenter\" style=\"margin-top:5px;margin-bottom:10px;border-top:1px dashed #aaa;display:none\"><span id=\"spvideo-desc-show-less\" class=\"ow_lbutton\" style=\"margin-top:-8px\">Show less</span></div>').insertAfter($('.ow_video_description'));
				$('<div id=\"video-show-more\" class=\"ow_small ow_txtcenter\" style=\"margin-top:5px;margin-bottom:10px;border-top:1px dashed #aaa\"><span id=\"spvideo-desc-show-more\" class=\"ow_lbutton\" style=\"margin-top:-8px\">Show more</span></div>').insertAfter($('.ow_video_description'));      

				$('#spvideo-desc-show-more').click(function(){
					$('.ow_video_description').animate({'max-height':$('.ow_video_description').attr('origheight')+'px'},300,function(){
            $('#spvideo-desc-show-more').parent().hide();
            $('#spvideo-desc-show-less').parent().show(); 
          });
									
				});
        $('#spvideo-desc-show-less').click(function(){
          $('.ow_video_description').css({'overflow':'hidden'});
          $('.ow_video_description').animate({'max-height':'60px'},300,function(){
            $('#spvideo-desc-show-less').parent().hide();
            $('#spvideo-desc-show-more').parent().show();
          });
          
        });
			}			
		");
  }

  /**
   * 
   */
  public static function correctPlayerSize(BASE_CLASS_EventCollector $event) {
  	OW::getDocument()->addOnloadScript("
    	$('.ow_video_player iframe').width($('.ow_video_player iframe').parent().width());
    ");
  }

  /**
   * 
   */
  public static function addLargerPlayerButton(BASE_CLASS_EventCollector $event) {
  	$event->add(
			array(
				'href' => 'javascript:;',
				'id' => 'btn-resize-player',
				'class' => 'btn-resize-player',
				'label' => 'Larger'
			)
		);
		OW::getDocument()->addStyleSheet(
				OW::getPluginManager()
				->getPlugin( 'spvideo' )
				->getStaticCssUrl().'spvideo_player.css'
		);
  	OW::getDocument()->addOnloadScript("
  		$('<div id=\"enlarged-remaining\" class=\"ow_superwide ow_left\" style=\"display:none\"></div>').insertAfter($('.ow_video_player').parent());
  		$('#btn-resize-player').click(function(){
  			var parent = $('.ow_video_player').parent();
  			var player = $('.ow_video_player');
  			var iframe = $('.ow_video_player iframe');
  			var remains = $('#enlarged-remaining');
  			var origHeight = player.height();

				if (player.attr('data-origheight')) {
					origHeight = parseInt(player.attr('data-origheight'));
				} else {
					player.attr('data-origheight',origHeight);
				}

  			if (parent.hasClass('ow_left')) {
          $('.ow_right').animate({\"margin-top\": player.height()+\"px\"}, 500, function(){
            $('.ow_right').css('margin-top','');
            parent.removeClass();
            parent.addClass('ow_stdmargin');
            var newHeight = iframe.height()*( parent.width()/iframe.width() );
            if (newHeight > 520) {
              iframe.height(520);
            } else {
              iframe.height(newHeight);
            }
            iframe.width(parent.width());
            $('#btn-resize-player a').html('Smaller');          
            remains.show();
            $('.ow_video_description').appendTo(remains);
            $('#video-show-more').appendTo(remains);
            $('#video-show-less').appendTo(remains);
            $('div[id^=comments-video]').appendTo(remains);
          });					
  			} else {
          $('.ow_right').css('margin-top',player.height()+'px');
					parent.removeClass();
					parent.addClass('ow_superwide');
					parent.addClass('ow_left');
					iframe.width(parent.width());
					iframe.height(origHeight);
					$('#btn-resize-player a').html('Larger');
					$('.ow_video_description').appendTo(parent);
					$('#video-show-more').appendTo(parent);
					$('#video-show-less').appendTo(parent);
					$('div[id^=comments-video]').appendTo(parent);
					remains.hide();
          $('.ow_right').animate({\"margin-top\": \"0px\"}, 500);
  			}
  		});
    ");
  }
}
