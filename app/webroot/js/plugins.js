// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  arguments.callee = arguments.callee.caller;  
  if(this.console) console.log( Array.prototype.slice.call(arguments) );
};
// make it safe to use console.log always
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();)b[a]=b[a]||c})(window.console=window.console||{});


// place any jQuery/helper plugins in here, instead of separate, slower script files.

// load a string into the funky lil box at the top for alerts
var inform_timeout 	= 0;
var inform_wait 	= 5000;
function inform(string,type){
	if (!type){
		type = 'plain';
	}
	currentTime = new Date()
	inform_timeout = currentTime.getTime();
	
	obj = document.getElementById('inform_box');
	obj.className = type;
	obj.innerHTML = string;
	obj.style.display = 'block';
	setTimeout('inform_hide()',inform_wait+50);
}
function inform_hide(){
	currentTime = new Date()
	if (currentTime.getTime() >= inform_timeout + inform_wait){
		obj = document.getElementById('inform_box');
		obj.style.display = 'none';
	} else {
		setTimeout('inform_hide()',1000);
	}
}