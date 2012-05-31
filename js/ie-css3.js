/* 

 IE-CSS3.js - (c) 2009 by Keith Clark, freely distributable under the terms of the MIT license.

 www.keithclark.co.uk

 DOMAssistant by Robert Nyman is required and must be added before this script in the calling page.

*/
var isIE = /*@cc_on!@*/false;isIE&&/MSIE [5-8]/.test(navigator.userAgent)&&DOMAssistant.DOMReady(function(){function o(a){var b=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");b.open("GET",a,false);b.send();return b.status==200?b.responseText:""}function p(a){var b,c,d,e,k,l=[];a=a.replace(q,"");a=a.replace(r,function(x,y,s){l.push(s);return""});var f=a.match(t);if(f)for(var g=0;g<f.length;g++){ruleSet=u.exec(f[g]);e=ruleSet[1].split(",");k=ruleSet[2];for(var h=0;h<e.length;h++){a=e[h];if(a.indexOf(":not(")== -1)if(b=m.exec(a)){for(;b;){c=b[0];b=b.index;d=c.length;d=b+d;c="_iecss3-"+c.replace(v,"").replace(w,"-");DOMAssistant.$(a.substring(0,d)).addClass(c);a=a.substring(0,b)+"."+c+a.substring(d);b=m.exec(a)}e[h]=a}}f[g]=e.join(",")+"{"+k+"}"}return{imports:l,rulesets:f||[]}}function n(a){for(var b,c=[],d=p(o(a)),e=0;e<d.imports.length;e++){b=n(a.substring(0,a.lastIndexOf("/")+1)+d.imports[e]);c=c.concat(b)}return c.concat(d.rulesets)}for(var t=/([a-zA-Z\.#@*][\w\W]*?{[^{}]*(}|{[\w\W]*?}\s*}))/g,r=/@import\s*url\(\s*(["'])?(.*?)\1\s*\)[\w\W]*?;/g, q=/\/\*[^*]*\*+([^\/][^*]*\*+)*\//g,u=/^([\w\W]*?)\s*{\s*([\w\W]*?)\s*}$/,m=/MSIE [56]/.test(navigator.userAgent)?/\:((((first|last|only)-(child|-of-type))|empty)|((nth-(last-)?(child|of-type))\([^\)]*?\)))/:/\:(((last|only)-child|(only|first|last)-of-type|empty)|((nth-(last-)?(child|of-type))\([^\)]*?\)))/,v=/[):\s]/g,w=/[(+]/g,i=0;i<document.styleSheets.length;i++){var j=document.styleSheets[i];if(j.href!="")j.cssText=n(j.href).join("\n")}});