window.alert = function(str,title)
{
    var shield = document.createElement("DIV");
    shield.id = "shield";
    shield.style.position = "absolute";
    shield.style.left = "50%";
    shield.style.top = "40%";
    shield.style.width = "800px";
    shield.style.height = "466px";
    shield.style.marginLeft = "-400px";
    shield.style.marginTop = "-110px";
    shield.style.zIndex = "25";
    var alertFram = document.createElement("DIV");
    alertFram.id="alertFram";
    alertFram.style.position = "absolute";
    alertFram.style.width = "800px";
    alertFram.style.height = "466px";
    alertFram.style.left = "50%";
    alertFram.style.top = "40%";
    alertFram.style.marginLeft = "-400px";
    alertFram.style.marginTop = "-110px";
    alertFram.style.textAlign = "center";
    alertFram.style.lineHeight = "300px";
    alertFram.style.zIndex = "300";
    strHtml = "<ul style=\"list-style:none;margin:0px;padding:0px;width:100%; hight:100%; border:2px solid #45b7fd;\">\n";
    strHtml += " <li style=\"background:#fff;text-align:left;text-indent:1em;font-size:42px;font-weight:bold;height:100px;;line-height:100px;border-bottom:1px solid #45b7fd;color:#333;\">"+title+"</li>\n";
    strHtml += " <li style=\"background:#fff;text-align:center;font-size:36px;line-height:48px;padding-top:40px;height:200px;color:#333;border-bottom:1px solid #45b7fd;\">"+str+"</li>\n";
    strHtml += " <li style=\"background:#fff;text-align:center;font-weight:bold;height:120px;line-height:120px;\"><input type=\"button\" value=\"确 定\" onclick=\"doOk()\" style=\"width:400px;height:80px;background:#45b7fd; border-radius:10px;color:white;border:1px solid #45b7fd;font-size:40px;line-height:60px;outline:none;margin-top: 20px\"/></li>\n";
    strHtml += "</ul>\n";
    alertFram.innerHTML = strHtml;
    document.body.appendChild(alertFram);
    document.body.appendChild(shield);
    this.doOk = function(){
        alertFram.style.display = "none";
        shield.style.display = "none";
    }
    alertFram.focus();
    document.body.onselectstart = function(){return false;};
}
