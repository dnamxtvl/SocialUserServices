* Source code này nằm trong nhóm các service thuộc dự án xây dựng 1 mảng xã hội với các công nghệ sử dụng:
   - Backend: Laravel & Nestjs
   - Frontend: Nextjs
   - Giao tiếp: Http client,kafka,rebitmq,socket,webrtc
   - Database: Mysql và Mongodb
   - Cache: Redis
   - Môi trường: Docker - Laradock
   - Server: Aws ec2
     
* Kiến trúc Backend:
   - UserService: Laravel base Lucid Architecture dựa trên Domain Driven Design và Command Bus
   - PostService: Laravel base Domain Driven Design và Command Bus
   - ChatService: Nestjs base Domain Driven Design và CQRS
   - NotificationService: Nestjs base Service Layer
   - GroupService: Laravel base Domain Driven Design và Command Bus
   - AdminCms: Laravel base Domain Driven Design và Command Bus

* Mô tả UserService
  - Backend API: Laravel
  - Kiến trúc: Lucid Architecture dựa trên Domain Driven Design và Command Bus
  - Giao tiếp: Http client,kafka,socket
  - Caching: Redis
  - Database: Mysql và Mongodb
  - Cache: Redis
  - Các packages: Laravel passport,Laravel horizon,Laravel stevebauman/location,Laravel Predis,Laravel telescope(only local)

    ![image](https://github.com/dnamxtvl/SocialUserServices/assets/61748711/bd86b781-cc5f-45e3-bf58-b68a742cd676)
