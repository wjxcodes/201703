<!DOCTYPE>
<html>
<head>

	<title></title>
 <style type="text/css">
   canvas {display: block;}
  </style>
</head>
	

<body>
		<canvas id="can"></canvas>
</body>
<script type="text/javascript">
   var can=document.getElementById('can');
   var canv=can.getContext("2d");

   can.height=window.innerHeight;
   can.width=window.innerWidth;

   var text="01";
   text=text.split("");

   var font_size=16;
   var across=can.width/font_size;
   var flutter=[];
   for(var y=0;y<across;y++){
        flutter[y]=1;
   }

   function draw(){
   	canv.fillStyle="rgba(0, 0, 0, 0.05)";
   	canv.fillRect(0, 0, can.width,can.height);

   	canv.fillStyle="#0F0";
   	canv.font=font_size+"px arial";
   	  for(var i=0;i<flutter.length;i++){
           var texts=text[Math.floor(Math.random()*text.length)];
           canv.fillText(texts,i*font_size,flutter[i]*font_size);

           if(flutter[i]*font_size > can.height || Math.random() > 0.95)
		            flutter[i] = 0;
           flutter[i]++;
   	  }
   }
   setInterval(draw,33);
</script>
</html>
