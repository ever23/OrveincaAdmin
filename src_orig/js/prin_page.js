function imprSelec(muestra)
{var ficha=document.getElementById(muestra);var ventimp=open(ficha, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+1350+',height='+700+'');ventimp.document.write(ficha.innerHTML);ventimp.document.close();ventimp.print();ventimp.close();}


var popUpWin=0;

function popUpWindow(URLStr,w,h)

{

  if(popUpWin)

  {

    if(!popUpWin.closed) popUpWin.close();

  }

  popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+w+',height='+h+'');

}



// JavaScript Document