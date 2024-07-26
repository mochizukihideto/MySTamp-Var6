$(document).ready(function () {
    $('.calendar-stamp').hover(
        function (e) {
            var stamp = $(this).data('stamp');
            console.log('Hover stamp data:', stamp);  // デバッグ用

            var info = '<strong>' + (stamp.hobby || 'Unknown') + '</strong><br>' +
                '開始日: ' + (stamp.start_date || 'N/A') + '<br>' +
                '頻度: ' + (stamp.frequency_count || 'N/A') + '回/' + (stamp.frequency_type || 'N/A') + '<br>' +
                '所要時間: ' + (stamp.duration || 'N/A') + '分<br>' +
                '今度の目標達成まであと: ' + (stamp.days_until_goal !== undefined ? stamp.days_until_goal + '日' : 'N/A') + '<br>' +
                '継続期間: ' + (stamp.days_passed !== undefined ? stamp.days_passed + '日' : 'N/A') + '<br>' +
                '合計回数: ' + (stamp.total_count !== undefined ? stamp.total_count : 'N/A') + '回<br>' +
                '合計時間: ' + (stamp.total_hours !== undefined ? stamp.total_hours + '時間' + stamp.total_minutes + '分' : 'N/A') + '<br>' +
                '連続日数: ' + (stamp.days_passed !== undefined ? stamp.days_passed + '日' : 'N/A');

            $('#stamp-info').html(info).css({
                top: e.pageY + 10,
                left: e.pageX + 10,
                display: 'block'
            });
        },
        function () {
            $('#stamp-info').hide();
        }
    );

    $(document).mousemove(function (e) {
        if ($('#stamp-info').is(':visible')) {
            $('#stamp-info').css({
                top: e.pageY + 10,
                left: e.pageX + 10
            });
        }
    });
    $(document).on('click', '.diary-entry', function () {
        var fullContent = $(this).data('full-content');
        alert(fullContent);
    });

    // 日記追加ボタンのクリックイベント
    $('.add-diary-btn').click(function () {
        var date = $(this).data('date');
        // 日付を 'YYYY-MM-DD' 形式に変換（タイムゾーンを考慮）
        var formattedDate = formatDate(new Date(date));
        $('#diary-date').val(formattedDate);
        $('#diary-modal').css('display', 'block');
    });

    // モーダルを閉じる
    $('.close').click(function () {
        $('#diary-modal').css('display', 'none');
    });

    // 日記フォームの送信
    $('#diary-form').submit(function (e) {
        e.preventDefault();
        var date = $('#diary-date').val();
        var content = $('#diary-content').val();

        console.log('Sending date:', date); // デバッグ用

        $.ajax({
            url: 'save_diary.php',
            method: 'POST',
            data: { date: date, content: content },
            success: function (response) {
                console.log('Server response:', response); // デバッグ用
                if (response.success) {
                    alert('日記が保存されました');
                    location.reload(); // ページをリロード
                } else {
                    alert('エラーが発生しました: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log('Ajax error:', status, error); // デバッグ用
                alert('通信エラーが発生しました');
            }
        });

        $('#diary-modal').css('display', 'none');
    });

    // 日付をフォーマットする関数（タイムゾーンを考慮）
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
    }
});