<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>爬蟲進度</title>
    <style>
        .progress-bar {
            width: 100%;
            background-color: #f3f3f3;
            border-radius: 5px;
        }
        .progress-bar div {
            height: 30px;
            background-color: #4caf50;
            width: 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>爬蟲進度</h1>
    <div class="progress-bar">
        <div id="progress" style="width: 0;"></div>
    </div>
    <p>進度：<span id="progressText">0%</span></p>
    <p><span id="statusText">爬蟲正在進行中</span></p>

    <button onclick="startScraping()">開始爬蟲</button>
    <button onclick="stopScraping()">停止爬蟲</button>

    <script>
        let progressInterval;

        // 啟動爬蟲
        function startScraping() {
            fetch('/StartScraping')
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    updateProgress();  // 啟動進度更新
                });
        }

        // 停止爬蟲
        function stopScraping() {
            fetch('/StopScraping')
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    document.getElementById('statusText').textContent = "爬蟲已停止";
                    clearInterval(progressInterval); // 停止進度更新
                });
        }

        // 更新爬蟲進度
        function updateProgress() {
            progressInterval = setInterval(function() {
                fetch('/GetScrapeProgress')
                    .then(response => response.json())
                    .then(data => {
                        let progress = data.progress;
                        let status = data.status;
                        document.getElementById('progress').style.width = progress + '%';
                        document.getElementById('progressText').textContent = progress + '%';
                        document.getElementById('statusText').textContent = status;

                        // 如果爬蟲已停止，停止更新進度
                        if (status === "爬蟲已停止") {
                            clearInterval(progressInterval); // 停止進度更新
                        }
                    });
            }, 1000);  // 每秒更新一次
        }
    </script>
</body>
</html>
