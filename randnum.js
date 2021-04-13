//1- Generate the numbers
var res = '';
for (i = 0; i < 10; i++) {
    var rand = Math.ceil(Math.random() * 9);
    //2- store the numbers generated
    res += rand;
}
    
//3- Insert the result
document.write("<p>" + res + "</p>");
