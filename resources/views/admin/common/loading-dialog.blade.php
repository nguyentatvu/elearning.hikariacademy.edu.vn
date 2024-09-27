<style>
/* Spinner CSS */
.spinner {
    width: 40px;
    height: 40px;
    position: relative;
    margin: 0 auto;
}

.spinner > div {
    width: 33%;
    height: 33%;
    background-color: #333;
    border-radius: 100%;
    position: absolute;
    left: 33%;
    top: 33%;
    animation: bounce 1.2s infinite ease-in-out;
}

@keyframes bounce {
    0%, 100% {
        transform: scale(0);
    }

    50% {
        transform: scale(1);
    }
}
</style>
<script>
// Hàm để hiển thị spinner
function showLoadingSpinner() {
	// Lấy phần tử HTML của overlay và dialog
	var overlay = document.getElementById('overlay');
	var loadingDialog = document.getElementById('loadingDialog');
    overlay.style.display = 'block';
    loadingDialog.style.display = 'block';
}

// Hàm để tắt hiển thị spinner
function closeLoadingSpinner() {
	// Lấy phần tử HTML của overlay và dialog
	var overlay = document.getElementById('overlay');
	var loadingDialog = document.getElementById('loadingDialog');
    overlay.style.display = 'none';
    loadingDialog.style.display = 'none';
}

function initLoadingDialog() {
	// Lặp qua tất cả các liên kết và gắn sự kiện click
	var links = document.getElementsByTagName('a');
	for (var i = 0; i < links.length; i++) {
		// Kiểm tra nếu href của liên kết không chứa dấu #
		var href = links[i].getAttribute('href');
/*		
		if (href !== null && (href.indexOf('#') > -1 || href.indexOf('tel') > -1 || href.indexOf('mailto') > -1 || href.indexOf('javascript') > -1)) {
			// Ghi ra log
			console.log('Link đặc biệt:', href);
		}
*/		

		if (href !== null && !(href.indexOf('#') > -1 || href.indexOf('tel') > -1 || href.indexOf('mailto') > -1 || href.indexOf('javascript') > -1)) {
			// Ghi ra log
			links[i].addEventListener('click', function(event) {
				// Hiển thị dialog
				showLoadingSpinner();
			});
		}

	}
	// Lắng nghe sự kiện load của trang
	window.addEventListener('load', function() {
		// Gọi hàm closeLoadingSpinner() khi trang đã load xong
		closeLoadingSpinner();
	});
}
</script>
<div id="overlay" style="display: none; position: fixed; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(0,0,0,0.5); z-index: 999;"></div>
<div id="loadingDialog" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; border-radius: 10px; z-index: 1000;">
	<div style="font-size:15px;color:black;font-weight:bold;"><br>Đang xử lý, vui lòng chờ trong giây lát...</div>
    <div class="spinner"></div>
</div>