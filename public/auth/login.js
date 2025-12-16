const form = document.getElementById('formAuthentication');
const submitBtn = form.querySelector('button[type="submit"]');

let countdownInterval = null;

form.addEventListener('submit', function (e) {
    e.preventDefault();

    if (submitBtn.disabled) return;

    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
        .then(async response => {
            const data = await response.json();
            return {data, status: response.status};
        })
        .then(({data, status}) => {

            if (data.status) {
                window.location.href = data.redirect;
                return;
            }

            if (status === 423 || status === 429) {
                startCountdown(data.blocked_in);
                notyf.error(data.message);
                return;
            }

            if (data.attempts_left !== null && data.attempts_left !== undefined) {
                notyf.error(`Email və ya şifrə səhvdir. Qalan cəhd: ${data.attempts_left}`);
            } else {
                notyf.error('Email və ya şifrə səhvdir.');
            }
        })
        .catch(() => {
            notyf.error('Serverdə problem baş verdi!');
        });
});

const countdownBox = document.getElementById('loginCountdown');
const countdownTimer = document.getElementById('countdownTimer');

function startCountdown(seconds) {
    let timeLeft = seconds;

    submitBtn.disabled = true;
    countdownBox.classList.remove('d-none');
    countdownTimer.textContent = timeLeft;

    countdownInterval = setInterval(() => {
        timeLeft--;
        countdownTimer.textContent = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(countdownInterval);
            submitBtn.disabled = false;
            countdownBox.classList.add('d-none');
        }
    }, 1000);
}
