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
   - UserService: Laravel Domain Driven Design và Command Bus
   - PostService: Laravel Domain Driven Design và Command Bus https://github.com/dnamxtvl/SocialPostServices
   - ChatService: Nestjs Domain Driven Design và CQRS
   - NotificationService: Nestjs Service Layer
   - GroupService: Laravel Domain Driven Design và Command Bus
   - AdminCms: Nestjs base Domain Driven Design và CQRS

* Mô tả UserService(đang phát triển) https://docs.google.com/document/d/1dLUUgWvyjHj7dm0wxa9-3Vj1b-5eGNfn0vMWQxVTwRM/edit
  - Backend API: Laravel
  - Kiến trúc: Domain Driven Design và Command Bus
  - Giao tiếp: Http client,kafka,socket
  - Caching: Redis
  - Database: Mysql và Mongodb
  - Cache: Redis

    
