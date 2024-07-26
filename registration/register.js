document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // フォームデータの取得
        const formData = new FormData(form);
        let userData = {};
        for (let [key, value] of formData.entries()) {
            if (key !== 'password' && key !== 'confirm_password') {
                userData[key] = value;
            }
        }

        // 確認ダイアログの表示
        const confirmed = confirm('以下の情報で登録してよろしいですか？\n\n' + 
            Object.entries(userData).map(([key, value]) => `${key}: ${value}`).join('\n'));

        if (confirmed) {
            // はいを選択した場合、フォームを送信
            form.submit();
        }
        // いいえの場合は何もせず、ユーザーが修正できるようにする
    });
});