# user_service
Demo service in microservice architecture

## 指令

### 啟動開發環境
`docker-composer up`

### 關閉開發環境
`docker-composer down`

### 初始化安裝依賴（安裝依賴後須重啟容器）
`docker-compose exec user-service composer install`
`docker-compose restart`

### 初始化資料庫
`docker-compose exec user-service php public/index.php init db`

### 修復資料表自動遞增主鍵問題
#### V1 資料表
`docker-compose exec user-service php public/index.php fix v1PK`
#### V2 資料表
`docker-compose exec user-service php public/index.php fix v2PK`

### 遷移資料庫結構到最新
`docker-compose exec user-service php spark migrate`

### 執行資料表資料填充
`docker-compose exec user-service php spark db:seed UserSeeder`

### 還原資料庫遷移至最舊
`docker-compose exec user-service php spark migrate:refresh`

### 更新 API 結構檔案
`docker-compose exec user-service php spark api:update`

### 清除快取
`docker-compose exec user-service php spark cache:clear`

### API 結構檔案更新
`docker-compose exec user-service php spark api:update`

### 執行單元測試
`docker-compose exec user-service vendor/bin/phpunit`
