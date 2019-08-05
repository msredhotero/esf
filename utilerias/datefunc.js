function compareDates (value1, value2) {
   var date1, date2;
   var month1, month2;
   var year1, year2;
//   var myDate_ini = new Date;
//   var myDate_fin = new Date;

   date1 = value1.substring (0, value1.indexOf ("/"));
   month1 = value1.substring (value1.indexOf ("/")+1, value1.lastIndexOf ("/"));
   year1 = value1.substring (value1.lastIndexOf ("/")+1, value1.length);

	month1 = month1 - 1;

   date2 = value2.substring (0, value2.indexOf ("/"));
   month2 = value2.substring (value2.indexOf ("/")+1, value2.lastIndexOf ("/"));
   year2 = value2.substring (value2.lastIndexOf ("/")+1, value2.length);

	month2 = month2 - 1;
/*
   myDate_ini.setDate(date1);
   myDate_ini.setMonth(month1);
   myDate_ini.setFullYear(year1);

   myDate_fin.setDate(30);
   myDate_fin.setMonth(month2);
   myDate_fin.setFullYear(year2);
*/
	myDate_ini = new Date(year1,month1,date1) 
	myDate_fin = new Date(year2,month2,date2) 

if (myDate_ini > myDate_fin){
        return false;
}else{
        return true;
}
}

function currentdate(){
var now = new Date();
var monthnumber = now.getMonth();
var monthday    = now.getDate();
var year        = now.getYear();

if (monthnumber < 10) monthnumber = "0" + monthnumber;
if (monthday < 10) monthday = "0" + monthday;

var diact = monthday+"/"+monthnumber+"/"+year;

return diact;
	
}
