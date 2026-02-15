# GitHub 업로드 스크립트
# PowerShell에서 실행하세요

Write-Host "================================" -ForegroundColor Cyan
Write-Host "  NeuronAIPHP GitHub 업로드" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# 현재 디렉토리 확인
$currentPath = Get-Location
Write-Host "현재 위치: $currentPath" -ForegroundColor Yellow
Write-Host ""

# Git 초기화
Write-Host "[1/6] Git 초기화 중..." -ForegroundColor Green
git init

# 모든 파일 추가
Write-Host "[2/6] 파일 스테이징 중..." -ForegroundColor Green
git add .

# 커밋
Write-Host "[3/6] 커밋 생성 중..." -ForegroundColor Green
git commit -m "Initial commit: AI_PHP Board System v1.0"

# 원격 저장소 연결
Write-Host "[4/6] 원격 저장소 연결 중..." -ForegroundColor Green
git remote add origin git@github.com:1clickmake/NeuronAIPHP.git

# 브랜치 이름 설정
Write-Host "[5/6] 브랜치 설정 중..." -ForegroundColor Green
git branch -M main

# 푸시
Write-Host "[6/6] GitHub에 푸시 중..." -ForegroundColor Green
git push -u origin main

Write-Host ""
Write-Host "================================" -ForegroundColor Cyan
Write-Host "  업로드 완료!" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "저장소 주소: https://github.com/1clickmake/NeuronAIPHP" -ForegroundColor Yellow
Write-Host ""
