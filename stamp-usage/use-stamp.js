document.addEventListener('DOMContentLoaded', function() {
    const stampsContainer = document.getElementById('stamps-container');
    const encouragementPopup = document.getElementById('encouragementPopup');
    const goalAchievedPopup = document.getElementById('goalAchievedPopup');
    const closeButtons = document.querySelectorAll('.close');
    const encouragementImage = document.getElementById('encouragementImage');
    const encouragementMessage = document.getElementById('encouragementMessage');
    const goalAchievedImage = document.getElementById('goalAchievedImage');
    const goalAchievedMessage = document.getElementById('goalAchievedMessage');

    stampsContainer.addEventListener('click', function(e) {
        const stampElement = e.target.closest('.stamp');
        if (stampElement) {
            const stampId = stampElement.dataset.stampId;
            const isGoalAchieved = stampElement.dataset.goalAchieved === 'true';

            updateStampUsage(stampId, stampElement);

            if (isGoalAchieved) {
                if (stampElement.dataset.goalImage && stampElement.dataset.goalImage !== "undefined") {
                    goalAchievedImage.src = stampElement.dataset.goalImage;
                    goalAchievedImage.style.display = 'block';
                } else {
                    goalAchievedImage.style.display = 'none';
                }
                goalAchievedMessage.textContent = stampElement.dataset.goalMessage || "目標達成おめでとうございます！";
                goalAchievedPopup.style.display = 'block';
            } else {
                if (stampElement.dataset.encouragementImage && stampElement.dataset.encouragementImage !== "undefined") {
                    encouragementImage.src = stampElement.dataset.encouragementImage;
                    encouragementImage.style.display = 'block';
                } else {
                    encouragementImage.style.display = 'none';
                }
                encouragementMessage.textContent = stampElement.dataset.encouragementMessage || "がんばりましたね！";
                encouragementPopup.style.display = 'block';
            }
        }
    });

    closeButtons.forEach(button => {
        button.onclick = function() {
            encouragementPopup.style.display = 'none';
            goalAchievedPopup.style.display = 'none';
        }
    });

    window.onclick = function(event) {
        if (event.target == encouragementPopup || event.target == goalAchievedPopup) {
            encouragementPopup.style.display = 'none';
            goalAchievedPopup.style.display = 'none';
        }
    }
});

function updateStampUsage(stampId, stampElement) {
    fetch('../stamp-usage/update_stamp_continuity.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ stamp_id: stampId })
    })
    .then(response => response.text())
    .then(text => {
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Error parsing JSON:', text);
            throw new Error('Invalid JSON response');
        }
    })
    .then(data => {
        if (data.success) {
            console.log('Stamp continuity updated successfully');
            updateStampInfo(stampElement, data);
            if (window.opener && !window.opener.closed) {
                window.opener.location.reload();
            }
        } else {
            console.error('Error updating stamp continuity:', data.message);
            updateStampInfo(stampElement);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        updateStampInfo(stampElement);
    });
}

function updateStampInfo(stampElement, data = null) {
    const streakElement = stampElement.querySelector('.streak-count');
    const daysLeftElement = stampElement.querySelector('.days-left');
    if (data) {
        if (streakElement) {
            streakElement.textContent = `${data.streak_count}日連続`;
        }
        if (daysLeftElement) {
            daysLeftElement.textContent = `今度の目標達成まであと${data.days_until_goal}日`;
        }
        stampElement.dataset.daysPassed = data.streak_count;
        stampElement.dataset.daysLeft = data.days_until_goal;
        stampElement.dataset.streakCount = data.streak_count;
        stampElement.dataset.goalAchieved = (data.days_until_goal === 0).toString();
    } else {
        const currentDaysPassed = parseInt(stampElement.dataset.daysPassed) || 0;
        const currentDaysLeft = parseInt(stampElement.dataset.daysLeft) || 0;
        if (streakElement) {
            streakElement.textContent = `${currentDaysPassed + 1}日連続`;
        }
        if (daysLeftElement) {
            daysLeftElement.textContent = `今度の目標達成まであと${Math.max(0, currentDaysLeft - 1)}日`;
        }
    }
}