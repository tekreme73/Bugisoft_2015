
$(document).ready(function() {
	win = true;
	life = 3;
	score= 0;
	delay = 1000;
	
	start();
	
	
	
	function start(){
	    win = true;
	    life = 3;
	    score= 0;
	    delay = 800;
    	
    	var interval = setInterval(function(){
    		if(win == true){
    			  popUpGoodOrBad();
    			  
    		}else{
    			 clearInterval(interval);
    			 $('.popUP').remove();
    			 $('#img_background').attr('src','assets/image/logo/Mascotte - triste-01.png');
    			 var divGameOver = $("<div id='gameover'/>");
    			 divGameOver.html("Renne over! ");
    			 divGameOver.addClass("gameOver");
    			 $('section').append(divGameOver);
    			 $('#pobel').html("Renne over!");
    		}
    		
    		delay = delay - delay*0.05;
    	},delay);
	
	}
	
	

    //tirage popUp
	function popUpGoodOrBad(){
		var alea = Math.random();
		if (alea>=0.5)
			popUpGood();
		else {
			popUpBad();
		}
	}


    //popUp good
	function popUpGood(){
		var popup = $('<div/>')
		popup.addClass("popUP").addClass("good");
		var img = $("<image src='assets/image/logo/Mascotte - heureux-01.png' />");
		img.width(100); 
		popup.append(img);
		$('#main_lab').append(popup);
		
		setpopUp(popup);
		
// 		$(popup).css({
// 			'top':$('#main_lab').position().top+Math.floor(Math.random() * $('#main_lab').height()) -$(popup).height() ,//Math.floor(Math.random() * ($('#main_lab').height()- $(popup).height())),
// 			'left':  $('#main_lab').position().left+Math.floor(Math.random() * $('#main_lab').width()) -$(popup).width() //Math.floor(Math.random() * ($('#main_lab').width()- $(popup).width()))
// 		});

        setTimeout(function(){
            popup.remove();
            score = score - 3;
            $('#deplacement_lab span').html(score);
           
            isGameOver();
        },delay*5);
        
		popup.click(function(){
			popup.remove();
			score = score+5;
			$('#deplacement_lab span').html(score);

		});

	}


    //popUp bad
	function popUpBad(){
		var popup = $('<div> </div>');
		popup.addClass("popUP").addClass("bad");
		var img = $("<image src='assets/image/logo/Mascotte - pascontent-01.png' />");
		img.width(100); 
		popup.append(img);
		$('#main_lab').append(popup);

		
        setpopUp(popup);

// 		$(popup).css({
// 			'top': $('#main_lab').position().top+Math.floor(Math.random() * $('#main_lab').height()) -$(popup).height(),
// 			'left': $('#main_lab').position().left+Math.floor(Math.random() * $('#main_lab').width()) -$(popup).width()
// 		});
		
		popup.click(function(){
			popup.remove();
			life = life-1;
			isGameOver();
		});

	}
	
	function isGameOver(){
	    if(life == 0 || delay<50){
			  win = false;
	    }
	    
	    
	}

    function getRandomIntInclusive(min, max) {
        return Math.floor(Math.random() * (max - min +1)) + min;
    }
    
    
    function setpopUp(popup){
        var random = Math.random();
        var random2 = Math.random();
        if (random > 0.8){
            
           random = random-0.1;
            
            
        }else if(random < 0.2) {
            
            random = random+0.1;
            
        }
            
        $(popup).css({
		    'top': $('#main_lab').position().top+Math.floor(random * $('#main_lab').height()) -$(popup).height()
	    });
            
        
        
        
        if (random2 > 0.8){
            
            random2 = random2-0.1;
            
            
        }else if(random2 < 0.2) {
            
            random2 = random2+0.1
            
        }
            
        $(popup).css({
		    'left': $('#main_lab').position().left+Math.floor(random2 * $('#main_lab').width()) -$(popup).width()
	    });
            
        
    }
});
