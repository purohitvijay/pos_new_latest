// Add this to the JavaScript tab of a Code Block, right at the top of the page
jQuery(document).ready(function(){
 
    // Animations
    jQuery('.cssAnimate').waypoint(function (direction) {
        
        // If scrolling down
        if (direction === 'down') {
        
          //Attention seekers   
          if (jQuery(this.element).hasClass('bounce')) {
            jQuery(this.element).addClass('animate__animated animate__bounce').removeClass('cssAnimate');
          }      
          if (jQuery(this.element).hasClass('pulse')) {
            jQuery(this.element).addClass('animate__animated animate__pulse').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('rubberBand')) {
            jQuery(this.element).addClass('animate__animated animate__rubberBand').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('shakeX')) {
            jQuery(this.element).addClass('animate__animated animate__shakeX').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('shakeY')) {
            jQuery(this.element).addClass('animate__animated animate__shakeY').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('headShake')) {
            jQuery(this.element).addClass('animate__animated animate__headShake').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('swing')) {
            jQuery(this.element).addClass('animate__animated animate__swing').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('tada')) {
            jQuery(this.element).addClass('animate__animated animate__tada').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('wobble')) {
            jQuery(this.element).addClass('animate__animated animate__wobble').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('jello')) {
            jQuery(this.element).addClass('animate__animated animate__jello').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('heartBeat')) {
            jQuery(this.element).addClass('animate__animated animate__heartBeat').removeClass('cssAnimate');
          } 
          
          //Back entrances   
          if (jQuery(this.element).hasClass('backInDown')) {
            jQuery(this.element).addClass('animate__animated animate__backInDown').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('backInLeft')) {
            jQuery(this.element).addClass('animate__animated animate__backInLeft').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('backInRight')) {
            jQuery(this.element).addClass('animate__animated animate__backInRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('backInUp')) {
            jQuery(this.element).addClass('animate__animated animate__backInUp').removeClass('cssAnimate');
          } 
    
          //Back exits   
          if (jQuery(this.element).hasClass('backOutDown')) {
            jQuery(this.element).addClass('animate__animated animate__backOutDown').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('backOutLeft')) {
            jQuery(this.element).addClass('animate__animated animate__backOutLeft').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('backOutRight')) {
            jQuery(this.element).addClass('animate__animated animate__backOutRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('backOutUp')) {
            jQuery(this.element).addClass('animate__animated animate__backOutUp').removeClass('cssAnimate');
          }   
    
          //Bouncing entrances  
          if (jQuery(this.element).hasClass('bounceIn')) {
            jQuery(this.element).addClass('animate__animated animate__bounceIn').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('bounceInDown')) {
            jQuery(this.element).addClass('animate__animated animate__bounceInDown').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('bounceInLeft')) {
            jQuery(this.element).addClass('animate__animated animate__bounceInLeft').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('bounceInRight')) {
            jQuery(this.element).addClass('animate__animated animate__bounceInRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('bounceInUp')) {
            jQuery(this.element).addClass('animate__animated animate__bounceInUp').removeClass('cssAnimate');
          }  
    
          //Bouncing exits  
          if (jQuery(this.element).hasClass('bounceOut')) {
            jQuery(this.element).addClass('animate__animated animate__bounceOut').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('bounceOutDown')) {
            jQuery(this.element).addClass('animate__animated animate__bounceOutDown').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('bounceOutLeft')) {
            jQuery(this.element).addClass('animate__animated animate__bounceOutLeft').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('bounceOutRight')) {
            jQuery(this.element).addClass('animate__animated animate__bounceOutRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('bounceOutUp')) {
            jQuery(this.element).addClass('animate__animated animate__bounceOutUp').removeClass('cssAnimate');
          }             
          
          //Fading entrances   
          if (jQuery(this.element).hasClass('fadeIn')) {
            jQuery(this.element).addClass('animate__animated animate__fadeIn').removeClass('cssAnimate');
          }
          if (jQuery(this.element).hasClass('fadeInDown')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInDown').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeInDownBig')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInDownBig').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeInLeft')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInLeft').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeInLeftBig')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInLeftBig').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeInRight')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInRight').removeClass('cssAnimate');
          }
          if (jQuery(this.element).hasClass('fadeInRightBig')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInRightBig').removeClass('cssAnimate');
          }
          if (jQuery(this.element).hasClass('fadeInUp')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInUp').removeClass('cssAnimate');
          }   
          if (jQuery(this.element).hasClass('fadeInUpBig')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInUpBig').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeInTopLeft')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInTopLeft').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeInTopRight')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInTopRight').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeInBottomLeft')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInBottomLeft').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeInBottomRight')) {
            jQuery(this.element).addClass('animate__animated animate__fadeInBottomRight').removeClass('cssAnimate');
          } 
    
          //Fading exits   
          if (jQuery(this.element).hasClass('fadeOut')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOut').removeClass('cssAnimate');
          }
          if (jQuery(this.element).hasClass('fadeOutDown')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutDown').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeOutDownBig')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutDownBig').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeOutLeft')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutLeft').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeOutLeftBig')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutLeftBig').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeOutRight')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutRight').removeClass('cssAnimate');
          }
          if (jQuery(this.element).hasClass('fadeOutRightBig')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutRightBig').removeClass('cssAnimate');
          }
          if (jQuery(this.element).hasClass('fadeOutUp')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutUp').removeClass('cssAnimate');
          }   
          if (jQuery(this.element).hasClass('fadeOutUpBig')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutUpBig').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeOutTopLeft')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutTopLeft').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeOutTopRight')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutTopRight').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeOutBottomLeft')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutBottomLeft').removeClass('cssAnimate');
          } 
          if (jQuery(this.element).hasClass('fadeOutBottomRight')) {
            jQuery(this.element).addClass('animate__animated animate__fadeOutBottomRight').removeClass('cssAnimate');
          }  
    
          //Flippers  
          if (jQuery(this.element).hasClass('flip')) {
            jQuery(this.element).addClass('animate__animated animate__flip').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('flipInX')) {
            jQuery(this.element).addClass('animate__animated animate__flipInX').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('flipInY')) {
            jQuery(this.element).addClass('animate__animated animate__flipInY').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('flipOutX')) {
            jQuery(this.element).addClass('animate__animated animate__flipOutX').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('flipOutY')) {
            jQuery(this.element).addClass('animate__animated animate__flipOutY').removeClass('cssAnimate');
          } 
    
          //Lightspeed  
          if (jQuery(this.element).hasClass('lightSpeedInRight')) {
            jQuery(this.element).addClass('animate__animated animate__lightSpeedInRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('lightSpeedInLeft')) {
            jQuery(this.element).addClass('animate__animated animate__lightSpeedInLeft').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('lightSpeedOutRight')) {
            jQuery(this.element).addClass('animate__animated animate__lightSpeedOutRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('lightSpeedOutLeft')) {
            jQuery(this.element).addClass('animate__animated animate__lightSpeedOutLeft').removeClass('cssAnimate');
          }
    
          //Rotating entrances  
          if (jQuery(this.element).hasClass('rotateIn')) {
            jQuery(this.element).addClass('animate__animated animate__rotateIn').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('rotateInDownLeft')) {
            jQuery(this.element).addClass('animate__animated animate__rotateInDownLeft').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('rotateInDownRight')) {
            jQuery(this.element).addClass('animate__animated animate__rotateInDownRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('rotateInUpLeft')) {
            jQuery(this.element).addClass('animate__animated animate__rotateInUpLeft').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('rotateInUpRight')) {
            jQuery(this.element).addClass('animate__animated animate__rotateInUpRight').removeClass('cssAnimate');
          } 
    
          //Rotating exits  
          if (jQuery(this.element).hasClass('rotateOut')) {
            jQuery(this.element).addClass('animate__animated animate__rotateOut').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('rotateOutDownLeft')) {
            jQuery(this.element).addClass('animate__animated animate__rotateOutDownLeft').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('rotateOutDownRight')) {
            jQuery(this.element).addClass('animate__animated animate__rotateOutDownRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('rotateOutUpLeft')) {
            jQuery(this.element).addClass('animate__animated animate__rotateOutUpLeft').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('rotateOutUpRight')) {
            jQuery(this.element).addClass('animate__animated animate__rotateOutUpRight').removeClass('cssAnimate');
          }   
    
          //Specials 
          if (jQuery(this.element).hasClass('hinge')) {
            jQuery(this.element).addClass('animate__animated animate__hinge').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('jackInTheBox')) {
            jQuery(this.element).addClass('animate__animated animate__jackInTheBox').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('rollIn')) {
            jQuery(this.element).addClass('animate__animated animate__rollIn').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('rollOut')) {
            jQuery(this.element).addClass('animate__animated animate__rollOut').removeClass('cssAnimate');
          }  
    
          //Zooming entrances  
          if (jQuery(this.element).hasClass('zoomIn')) {
            jQuery(this.element).addClass('animate__animated animate__zoomIn').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('zoomInDown')) {
            jQuery(this.element).addClass('animate__animated animate__zoomInDown').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('zoomInRight')) {
            jQuery(this.element).addClass('animate__animated animate__zoomInRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('zoomInLeft')) {
            jQuery(this.element).addClass('animate__animated animate__zoomInLeft').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('zoomInUp')) {
            jQuery(this.element).addClass('animate__animated animate__zoomInUp').removeClass('cssAnimate');
          } 
    
          //Zooming exits  
          if (jQuery(this.element).hasClass('zoomOut')) {
            jQuery(this.element).addClass('animate__animated animate__zoomOut').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('zoomOutDown')) {
            jQuery(this.element).addClass('animate__animated animate__zoomOutDown').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('zoomOutRight')) {
            jQuery(this.element).addClass('animate__animated animate__zoomOutRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('zoomOutLeft')) {
            jQuery(this.element).addClass('animate__animated animate__zoomOutLeft').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('zoomOutUp')) {
            jQuery(this.element).addClass('animate__animated animate__zoomOutUp').removeClass('cssAnimate');
          }
    
          //Sliding entrances  
          if (jQuery(this.element).hasClass('slideInDown')) {
            jQuery(this.element).addClass('animate__animated animate__slideInDown').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('slideInLeft')) {
            jQuery(this.element).addClass('animate__animated animate__slideInLeft').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('slideInRight')) {
            jQuery(this.element).addClass('animate__animated animate__slideInRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('slideInUp')) {
            jQuery(this.element).addClass('animate__animated animate__slideInUp').removeClass('cssAnimate');
          } 
    
          //Sliding exits    
          if (jQuery(this.element).hasClass('slideOutDown')) {
            jQuery(this.element).addClass('animate__animated animate__slideOutDown').removeClass('cssAnimate');
          }       
          if (jQuery(this.element).hasClass('slideOutLeft')) {
            jQuery(this.element).addClass('animate__animated animate__slideOutLeft').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('slideOutRight')) {
            jQuery(this.element).addClass('animate__animated animate__slideOutRight').removeClass('cssAnimate');
          }  
          if (jQuery(this.element).hasClass('slideOutUp')) {
            jQuery(this.element).addClass('animate__animated animate__slideOutUp').removeClass('cssAnimate');
          }
    
        }
    }, { offset: '70%' });
      
    });