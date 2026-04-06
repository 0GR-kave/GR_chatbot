function sendMessage() {
    let input = document.getElementById("userInput").value;
    let chatbox = document.getElementById("chatbox");

    // User message
    let userMsg = document.createElement("div");
    userMsg.className = "user";
    userMsg.innerText = input;
    chatbox.appendChild(userMsg);

    fetch("chatbot.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "message=" + encodeURIComponent(input)
    })
    .then(res => res.text())
    .then(res => {

        // Check if code
        if (res.includes("<") || res.includes("{") || res.includes(";")) {

            let pre = document.createElement("pre");
            pre.className = "bot";
            pre.textContent = res;

            // Copy button
            let btn = document.createElement("button");
            btn.innerText = "Copy";
            btn.onclick = () => {
                navigator.clipboard.writeText(res);
                alert("Copied!");
            };

            chatbox.appendChild(pre);
            chatbox.appendChild(btn);

        } else {

            let botMsg = document.createElement("div");
            botMsg.className = "bot";
            botMsg.innerText = res;
            chatbox.appendChild(botMsg);

        }

        chatbox.scrollTop = chatbox.scrollHeight;
    });

    document.getElementById("userInput").value = "";
}


function loadHistory(){
    fetch("history.php")
    .then(res => res.text())
    .then(data => {
        document.getElementById("chatbox").innerHTML = data;
    });

}