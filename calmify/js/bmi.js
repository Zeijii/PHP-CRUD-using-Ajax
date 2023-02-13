function CalculateBMI(){
    var height = document.getElementById("h").value;
    var weight = document.getElementById("w").value;

    var result = parseFloat(weight) /(parseFloat(height)/100)**2;

    if(!isNaN(result)){
        document.getElementById("output").innerHTML = result;
        if(result < 18.5){
            document.getElementById("status").innerHTML = "Underweight";
        }
        else if(result < 25){
            document.getElementById("status").innerHTML = "Healthy";
        }
        else if(result < 30){
            document.getElementById("status").innerHTML = "Overweight";
        }
        else{
            document.getElementById("status").innerHTML = "Obesity";
        }
    }
}