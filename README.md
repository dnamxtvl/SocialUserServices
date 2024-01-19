* Source code này nằm trong nhóm các service thuộc dự án xây dựng 1 mảng xã hội với các công nghệ sử dụng:
   - Backend: Laravel & Nestjs
   - Frontend: Nextjs
   - Giao tiếp: Http client,kafka,rebitmq,socket,webrtc
   - Database: Mysql và Mongodb
   - Search engine: Elasticsearch
   - Cache: Redis
   - Môi trường: Docker - Laradock
   - Server: Aws ec2
     
* Kiến trúc Backend:
   - UserService: Laravel base Lucid Architecture dựa trên Domain Driven Design và Command Bus
   - PostService: Laravel base Domain Driven Design và Command Bus
   - ChatService: Nestjs base Domain Driven Design và CQRS
   - NotificationService: Nestjs base Service Layer
   - GroupService: Laravel base Domain Driven Design và Command Bus
   - AdminCms: Nestjs base Domain Driven Design và CQRS

* Mô tả UserService(đang phát triển) https://docs.google.com/document/d/1dLUUgWvyjHj7dm0wxa9-3Vj1b-5eGNfn0vMWQxVTwRM/edit
  - Backend API: Laravel
  - Kiến trúc: Lucid Architecture dựa trên Domain Driven Design và Command Bus
  - Giao tiếp: Http client,kafka,socket
  - Caching: Redis
  - Database: Mysql và Mongodb
  - Cache: Redis
  - Các packages: Laravel passport,Laravel horizon,Laravel stevebauman/location,Laravel Predis,Laravel telescope(only local)...
  - Cấu trúc của 1 domain:
    ![image](https://github.com/dnamxtvl/SocialUserServices/assets/61748711/cf44b905-9f6e-4aac-b2ff-19727e5648d1)

    + Trong repository chứa các repository interface



  - Cấu trúc của data layer:
    ![image](https://github.com/dnamxtvl/SocialUserServices/assets/61748711/025a10b3-6897-4e21-800b-c8f8a94ae4ab)

    + Trong repository chứa các repository implement từ repository interface ở tầng domain


    
