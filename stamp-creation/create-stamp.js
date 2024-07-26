document.addEventListener('DOMContentLoaded', function () {
    const hobbyInput = document.getElementById('hobby');
    const fontPreview = document.getElementById('fontPreview');
    const fontInput = document.getElementById('font');
    const savedStamps = document.getElementById('savedStamps');
    const confirmationDialog = document.getElementById('confirmationDialog');
    const finalStamp = document.getElementById('finalStamp');
    const selectedStamp = document.getElementById('selectedStamp');
    const stampSelection = document.getElementById('stampSelection');
    const frequencyType = document.getElementById('frequencyType');
    const frequencyCount = document.getElementById('frequencyCount');
    const frequencyUnit = document.getElementById('frequencyUnit');
    const colorOptions = document.querySelectorAll('.color-option');
    const selectedColorInput = document.getElementById('selectedColor');
    let currentSelectedStamp = null;
    let currentStampId = null;

    const fonts = [
        { name: 'BebasNeue', label: 'Bebas Neue' },
        { name: 'DancingScript', label: 'Dancing Script' },
        { name: 'FredokaOne', label: 'Fredoka One' },
        { name: 'IndieFlower', label: 'Indie Flower' },
        { name: 'Pacifico', label: 'Pacifico' },
        { name: 'PermanentMarker', label: 'Permanent Marker' },
        { name: 'Roboto', label: 'Roboto' },
        { name: 'NotoSansJP', label: 'Noto Sans Japanese' },
        { name: 'KaisotaiNextUPB', label: 'Kaisotai Next UP B' },
        { name: 'TogaliteBlack', label: 'Togalite Black' },
        { name: 'Pigmo01', label: 'Pigmo01' }
    ];

    function createFontOptions() {
        fontPreview.innerHTML = '';
        fonts.forEach(font => {
            const div = document.createElement('div');
            div.className = 'font-option';
            div.style.fontFamily = font.name;
            div.textContent = hobbyInput.value || font.label;
            div.dataset.font = font.name;
            fontPreview.appendChild(div);

            div.addEventListener('click', function () {
                document.querySelectorAll('.font-option').forEach(el => el.classList.remove('selected'));
                this.classList.add('selected');
                fontInput.value = this.dataset.font;
            });
        });
    }

    function updateFontPreviews() {
        const color = selectedColorInput.value;
        document.querySelectorAll('.font-option').forEach(div => {
            div.textContent = hobbyInput.value || div.dataset.font;
            if (color === 'rainbow') {
                div.style.backgroundImage = 'linear-gradient(to right, red, orange, yellow, green, blue, indigo, violet)';
                div.style.webkitBackgroundClip = 'text';
                div.style.webkitTextFillColor = 'transparent';
            } else {
                div.style.color = color;
                div.style.backgroundImage = 'none';
                div.style.webkitTextFillColor = color;
            }
        });
    }

    createFontOptions();
    hobbyInput.addEventListener('input', updateFontPreviews);

    const defaultFont = document.querySelector('.font-option[data-font="BebasNeue"]');
    if (defaultFont) {
        defaultFont.classList.add('selected');
        fontInput.value = 'BebasNeue';
    }

    colorOptions.forEach(option => {
        option.addEventListener('click', function () {
            const color = this.dataset.color;
            selectedColorInput.value = color;
            colorOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            updateFontPreviews();
        });
    });

    colorOptions[0].click();

    function updateFrequencyUnit() {
        switch (frequencyType.value) {
            case 'daily':
                frequencyUnit.textContent = '回/日';
                frequencyCount.max = 24;
                document.getElementById('weekdaySelection').style.display = 'none';
                break;
            case 'weekly':
                frequencyUnit.textContent = '回/週';
                frequencyCount.max = 7;
                document.getElementById('weekdaySelection').style.display = 'block';
                break;
            case 'monthly':
                frequencyUnit.textContent = '回/月';
                frequencyCount.max = 31;
                document.getElementById('weekdaySelection').style.display = 'none';
                break;
        }
        if (parseInt(frequencyCount.value) > parseInt(frequencyCount.max)) {
            frequencyCount.value = frequencyCount.max;
        }
    }

    function updateStampList() {
        fetch('get_stamps.php')
            .then(response => response.json())
            .then(stamps => {
                const draftStampsContainer = document.getElementById('draftStampsList');
                const registeredStampsContainer = document.getElementById('registeredStampsList');

                if (!draftStampsContainer || !registeredStampsContainer) {
                    console.error('Required DOM elements not found');
                    return;
                }

                draftStampsContainer.innerHTML = '';
                registeredStampsContainer.innerHTML = '';

                stamps.forEach(stamp => {
                    const stampDiv = document.createElement('div');
                    stampDiv.className = `saved-stamp ${stamp.status}-stamp`;
                    stampDiv.dataset.stampId = stamp.id;
                    stampDiv.innerHTML = `
                        <img src="${stamp.image_path}" alt="${stamp.status} Stamp">
                        <p>${stamp.hobby}</p>
                    `;

                    if (stamp.status === 'draft') {
                        draftStampsContainer.appendChild(stampDiv);
                    } else if (stamp.status === 'registered') {
                        stampDiv.innerHTML += `
                            <p>開始日: ${stamp.start_date || 'N/A'}</p>
                            <p>頻度: ${stamp.frequency_count || 'N/A'} 回/${stamp.frequency_type || 'N/A'}</p>
                        `;
                        registeredStampsContainer.appendChild(stampDiv);
                    }
                });

                if (draftStampsContainer.children.length === 0) {
                    draftStampsContainer.innerHTML = '<p>作成中のスタンプはありません。</p>';
                }
                if (registeredStampsContainer.children.length === 0) {
                    registeredStampsContainer.innerHTML = '<p>登録済みスタンプはありません。</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching stamps:', error);
            });
    }

    if (savedStamps) {
        savedStamps.addEventListener('click', function (e) {
            const stampElement = e.target.closest('.draft-stamp');
            if (stampElement) {
                currentSelectedStamp = stampElement;
                currentStampId = stampElement.dataset.stampId;
                confirmationDialog.style.display = 'block';
            }
        });

        updateStampList();
    } else {
        console.error('savedStamps element not found');
    }

    document.getElementById('confirmYes').addEventListener('click', function () {
        if (currentSelectedStamp) {
            stampSelection.style.display = 'none';
            confirmationDialog.style.display = 'none';
            finalStamp.style.display = 'block';
            selectedStamp.innerHTML = currentSelectedStamp.innerHTML;
        }
    });

    document.getElementById('confirmNo').addEventListener('click', function () {
        confirmationDialog.style.display = 'none';
        currentSelectedStamp = null;
        currentStampId = null;
    });

    frequencyType.addEventListener('change', updateFrequencyUnit);

    updateFrequencyUnit();

    document.getElementById('stampForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('save_stamp.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json();
                }
            })
            .then(result => {
                if (result && !result.success) {
                    alert('エラー: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('スタンプ作成中にエラーが発生しました。');
            });
    });

    document.getElementById('additionalInfoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        data.stamp_id = currentStampId;

        if (data.frequencyType === 'weekly') {
            data.weekdays = Array.from(document.querySelectorAll('input[name="weekdays[]"]:checked')).map(cb => cb.value);
        }

        fetch('save_stamp_usage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    alert('習い事の情報が保存されました。');
                    updateStampList();  // スタンプリストを更新
                    finalStamp.style.display = 'none';
                    stampSelection.style.display = 'block';

                    // 作成中のスタンプを削除し、登録済みスタンプに移動
                    const draftStamp = document.querySelector(`.draft-stamp[data-stamp-id="${currentStampId}"]`);
                    if (draftStamp) {
                        draftStamp.remove();
                    }
                } else {
                    alert('エラー: ' + (result.error || result.message));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('保存中にエラーが発生しました: ' + error.message);
            });
    });
});