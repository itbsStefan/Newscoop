function GeneratePassword() {

    if (parseInt(navigator.appVersion) <= 3) {
        alert("Sorry this only works in 4.0+ browsers");
        return true;
    }

    var length=8;
    var sPassword = "";

    var noPunction = true;
    var randomLength = true;

    if (randomLength) {
        length = Math.random();

        length = parseInt(length * 100);
        length = (length % 7) + 6;
    }


    for (i=0; i < length; i++) {

        numI = getRandomNum();
        if (noPunction) { while (checkPunc(numI)) { numI = getRandomNum(); } }

        sPassword = sPassword + String.fromCharCode(numI);
    }

    document.forms[0].password.value = sPassword;
    document.forms[0].passwordConf.value = sPassword;
    document.getElementById('passtext').innerHTML = sPassword;

    chkPass(sPassword);

    return true;
}

function cleanGeneratedPasswords() {
    document.forms[0].password.value = '';
    document.forms[0].passwordConf.value = '';
    document.getElementById('passtext').innerHTML = '';
    var sPassword = '';
    chkPass(sPassword);
}

function getRandomNum() {

    // between 0 - 1
    var rndNum = Math.random();

    // rndNum from 0 - 1000
    rndNum = parseInt(rndNum * 1000);

    // rndNum from 33 - 127
    rndNum = (rndNum % 94) + 33;

    return rndNum;
}

function checkPunc(num) {

    if ((num >=33) && (num <=47)) { return true; }
    if ((num >=58) && (num <=64)) { return true; }
    if ((num >=91) && (num <=96)) { return true; }
    if ((num >=123) && (num <=126)) { return true; }

    return false;
}
