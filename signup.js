document.addEventListener("DOMContentLoaded", function() {
  const popup = document.getElementById("popup");
  const popupMessage = document.getElementById("popupMessage");
  const closeBtn = document.getElementById("closePopup");

  // Get popup message from PHP session
  fetch("./popup.php")
    .then(res => res.json())
    .then(data => {
      if (data.message) {
        popupMessage.innerHTML = data.message;
        popup.style.display = "flex";

        // Auto close after 4s
        setTimeout(() => {
          popup.style.display = "none";
        }, 4000);
      }
    });

  // Close button
  closeBtn.addEventListener("click", () => {
    popup.style.display = "none";
  });
});
