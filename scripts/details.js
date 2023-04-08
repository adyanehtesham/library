// function to handle the controls for the modal
displayDetails = (modalId) => {
    // get modal element using the id
    modal = document.getElementById('modal' + modalId);
    // display modal
    modal.style.display = "block";

    // get the close button
    const close = document.getElementsByClassName("close")[0];

    // when the user clicks on the close button, close the modal
    close.onclick = () => {
        modal.style.display = "none";
    }

    // when the user clicks anywhere outside of the modal, close the modal
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}