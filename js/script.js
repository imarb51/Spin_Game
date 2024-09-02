document.addEventListener('DOMContentLoaded', function() {
    const spinButton = document.getElementById('spin-button');
    const slots = [
        document.getElementById('slot1'),
        document.getElementById('slot2'),
        document.getElementById('slot3')
    ];
    const pointsDisplay = document.getElementById('points');
    const resultPopup = document.getElementById('result-popup');
    const resultTitle = document.getElementById('result-title');
    const resultMessage = document.getElementById('result-message');
    const resultOk = document.getElementById('result-ok');

    spinButton.addEventListener('click', spin);
    resultOk.addEventListener('click', closePopup);

    function spin() {
        spinButton.disabled = true;
        let spins = 0;
        const spinInterval = setInterval(() => {
            slots.forEach(slot => {
                slot.textContent = 'GOZOOP'[Math.floor(Math.random() * 6)];
            });
            spins++;
            if (spins >= 20) {
                clearInterval(spinInterval);
                checkResult();
            }
        }, 100);
    }

    function checkResult() {
        fetch('spin.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    slots.forEach((slot, index) => {
                        slot.textContent = data.result[index];
                    });
                    pointsDisplay.textContent = data.points;
                    showResultPopup(data);
                } else {
                    showResultPopup({ message: data.message, points: 0 });
                }
                spinButton.disabled = !data.canSpin;
            })
            .catch(error => {
                console.error('Error:', error);
                showResultPopup({ message: 'An error occurred. Please try again.', points: 0 });
                spinButton.disabled = false;
            });
    }

    function showResultPopup(data) {
        if (data.points > 0) {
            resultTitle.textContent = 'CONGRATULATIONS!';
            resultMessage.textContent = `You get ${data.points} points. Use this to redeem below products.`;
        } else {
            resultTitle.textContent = 'THAT WAS A GREAT SPIN!';
            resultMessage.textContent = 'One more try might make you lucky. To earn more spins click below.';
        }
        resultPopup.style.display = 'flex';
    }

    function closePopup() {
        resultPopup.style.display = 'none';
    }
});