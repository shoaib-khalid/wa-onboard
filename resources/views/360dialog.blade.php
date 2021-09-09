<html>
<head>
    <title>Symplified - Configure</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        button.disabled:hover {
            cursor:not-allowed
        }
        #cont_token_msg, #cont_botuname_msg {
            display: none;
        }
    </style>
</head>
<body class="bg-blue-700">
    <div class="container">
        <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <img class="mx-auto h-30 w-auto" src="{{ asset('images/logo-header.png') }}" alt="Workflow">
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Whatsapp Business API onboarding
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600">
                        with 360dialog
                        
                    </p>
                </div>
                <div class="flex items-center justify-center"> 
                    <a id="submitBtn" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded" 
                    href="https://www.facebook.com/v8.0/dialog/oauth?app_id=307713669880953&cbt=1631154870451&channel_url=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df225ca233e0f908%26domain%3Dhub.360dialog.com%26is_canvas%3Dfalse%26origin%3Dhttps%253A%252F%252Fhub.360dialog.com%252Ff3b90a9789086fc%26relation%3Dopener&client_id=307713669880953&display=popup&domain=hub.360dialog.com&e2e=%7B%7D&extras=%7B%22feature%22%3A%22whatsapp_embedded_signup%22%2C%22setup%22%3A%7B%7D%7D&fallback_redirect_uri=https%3A%2F%2Fhub.360dialog.com%2Fdashboard%2Fpartners%2Fpartner%2FSvAiK8PA%2Fpipes%2Fadd-number-flow&locale=en_US&logger_id=f7c08576a5cee4&origin=1&redirect_uri=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df37e2462aace2ec%26domain%3Dhub.360dialog.com%26is_canvas%3Dfalse%26origin%3Dhttps%253A%252F%252Fhub.360dialog.com%252Ff3b90a9789086fc%26relation%3Dopener%26frame%3Df19e6df35fc47&response_type=token%2Csigned_request%2Cgraph_domain&scope=business_management%2Cwhatsapp_business_management&sdk=joey&version=v8.0">
                    Start</a>
                </div>
            <div>
        </div>
    </div>
</body>
    <script>
        function loadDoc() {

            action = document.getElementById("submitBtn").innerHTML;

            if (action === "Submit") {
                let status = false;
                botuname = document.getElementById("botuname").value;
                userid = document.getElementById("userid").value;
                token = document.getElementById("token").value;

                // check whether input in empty
                document.getElementById("message").style.display = "none";
                if (!botuname || !userid  || !token ) {
                    document.getElementById("message").style.display = "block";
                    document.getElementById("message").className = "bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative my-5";
                    document.getElementById("message").innerHTML = "All field are required";
                    return false;
                }

                // check botusername validity
                var status_botuname = check_botuname();
                if (status_botuname !== true) {
                    document.getElementById("submitBtn").disabled = false;
                    document.getElementById("submitBtn").classList.remove("disabled");
                    return false;
                }

                // disabled submit button (to avoid double request by users)
                document.getElementById("submitBtn").disabled = true;
                document.getElementById("submitBtn").classList.add("disabled");

                var data = {
                    botuname : botuname,
                    userid : userid,
                    token : token
                };

                var csrf_token =  "{{ csrf_token() }}";

                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange  = function() {
                    if(xhttp.readyState == 4) {
                        if (xhttp.status == 200) {
                            document.getElementById("message").className = "bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-5";
                            document.getElementById("message").innerHTML = JSON.parse(xhttp.responseText)["description"];
                            // change submit button to close button
                            document.getElementById("submitBtn").innerHTML = "Close";
                        } else {
                            document.getElementById("message").className = "bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative my-5";
                            document.getElementById("message").innerHTML = JSON.parse(xhttp.responseText)["description"];
                        }
                        // re-display close button
                        document.getElementById("message").style.display = "block";
                        document.getElementById("submitBtn").disabled = false;
                        document.getElementById("submitBtn").classList.remove("disabled");
                    }
                }
                xhttp.open("POST", "/");
                xhttp.setRequestHeader("Content-Type", "application/json");
                xhttp.setRequestHeader("Accept", "application/json");
                xhttp.setRequestHeader("X-CSRF-Token",csrf_token);
                xhttp.send(JSON.stringify(data));
                
                return false;
            } else {
                window.open('','_self').close();
            }
        }

        function check_botuname() {
            let status = false;
            let botuname = document.getElementById("botuname");
            let botuname_msg = document.getElementById("botuname_msg");
            let cont_botuname_msg = document.getElementById("cont_botuname_msg");

            const numerical_char = new RegExp('^[0-9]+$','i');

            cont_botuname_msg.className = "bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative my-5";
            if (botuname.value == "") {
                botuname_msg.innerHTML = "Whatsapp business phonenumber can't be empty";
                cont_botuname_msg.style.display = "block";
            } else if (botuname.value.length > 50){
                botuname_msg.innerHTML = "Whatsapp business phonenumber length must not exceed 50 character";
                cont_botuname_msg.style.display = "block";
            } else if (!numerical_char.test(botuname.value)){
                botuname_msg.innerHTML = "Only numerical character is allowed";
                cont_botuname_msg.style.display = "block";
            } else {
                status = true;
                // botuname_msg.innerHTML = "Good";
                cont_botuname_msg.style.display = "none";
                // cont_botuname_msg.className = "bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-5";
            }
            return status;
        }
    </script>
</html>