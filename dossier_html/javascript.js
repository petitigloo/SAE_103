var negatif0 = document.getElementById("Calcul0");
var nb0 = parseInt(document.getElementById("Calcul0").innerText);
var negatif1 = document.getElementById("Calcul1");
var nb1 = parseInt(document.getElementById("Calcul1").innerText);
var negatif2 = document.getElementById("Calcul2");
var nb2 = parseInt(document.getElementById("Calcul2").innerText);
var negatif3 = document.getElementById("Calcul3");
var nb3 = parseInt(document.getElementById("Calcul3").innerText);
var negatif4 = document.getElementById("Calcul3");
var nb4 = parseInt(document.getElementById("Calcul3").innerText);
var negatif5 = document.getElementById("Calcul3");
var nb5 = parseInt(document.getElementById("Calcul3").innerText);
var negatif6 = document.getElementById("Calcul3");
var nb6 = parseInt(document.getElementById("Calcul3").innerText);

negatif0 = testNum0(nb0); 
negatif1 = testNum1(nb1); 
negatif2 = testNum2(nb2); 
negatif3 = testNum3(nb3); 
negatif4 = testNum4(nb4); 
negatif5 = testNum5(nb5); 
negatif6 = testNum6(nb6); 

function testNum0(a) {
    let result;
    if (a >= 0)
    {
        negatif0.classList.remove("active");
    } else {
        negatif0.classList.add("active");
    }
    return result;
}

function testNum1(a) {
    let result;
    if (a >= 0)
    {
        negatif1.classList.remove("active");
    } else {
        negatif1.classList.add("active");
    }
    return result;
}

function testNum2(a) {
    let result;
    if (a >= 0)
    {
        negatif2.classList.remove("active");
    } else {
        negatif2.classList.add("active");
    }
    return result;
}

function testNum3(a) {
    let result;
    if (a >= 0)
    {
        negatif3.classList.remove("active");
    } else {
        negatif3.classList.add("active");
    }
    return result;
}

function testNum4(a) {
    let result;
    if (a >= 0)
    {
        negatif4.classList.remove("active");
    } else {
        negatif4.classList.add("active");
    }
    return result;
}

function testNum5(a) {
    let result;
    if (a >= 0)
    {
        negatif5.classList.remove("active");
    } else {
        negatif5.classList.add("active");
    }
    return result;
}

function testNum6(a) {
    let result;
    if (a >= 0)
    {
        negatif6.classList.remove("active");
    } else {
        negatif6.classList.add("active");
    }
    return result;
}