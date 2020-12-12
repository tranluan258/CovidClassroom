USE MASTER
GO
CREATE DATABASE RestaurantManager
GO
USE RestaurantManager
GO
CREATE TABLE Account(
    username nvarchar(255) NOT NULL PRIMARY KEY,
    pass nvarchar(255) NOT NULL,
    role int NOT NULL,
    name nvarchar(255) NOT NULL,
    phone nvarchar(255) NOT NULL,
    address nvarchar(255) NOT NULL,
    email nvarchar(255) NOT NULL,
	gioitinh bit NOT NULL,
	ngaysinh date NOT NULL
)
GO
CREATE TABLE TalbleFood(
    id_table int identity(1,1) NOT NULL PRIMARY KEY,
    status bit NOT NULL default 0
)
GO
CREATE TABLE  CategoriesFood(
    id_category int identity(1,1) NOT NULL PRIMARY KEY,
    category nvarchar(255) NOT NULL
)
GO
CREATE TABLE Food(
    id_food int identity(1,1) NOT NULL PRIMARY KEY,
    foodname nvarchar(255) NOT NULL,
    id_category int NOT NULL,
	price float NOT NULL default 0
    FOREIGN KEY (id_category) REFERENCES CategoriesFood(id_category)
)
GO
CREATE TABLE  CategoriesDrink(
    id_category int identity(1,1) NOT NULL PRIMARY KEY,
    category nvarchar(255)
)
GO
CREATE TABLE Drink(
    id_drink int identity(1,1) NOT NULL PRIMARY KEY,
    drinkname nvarchar(255) NOT NULL,
    id_category int NOT NULL,
	price float NOT NULL default 0

    FOREIGN KEY (id_category) REFERENCES CategoriesDrink(id_category)
)
GO
CREATE TABLE Bill(
    id_bill int identity(1,1) NOT NULL PRIMARY KEY,
    Datecheckin Date NOT NULL default getdate(),
    Datecheckout Date,
    id_table int,
    status int not null default 0

    FOREIGN KEY (id_table) REFERENCES TalbleFood(id_table)
)
GO
CREATE TABLE Bill_info(
    id int identity(1,1) NOT NULL PRIMARY KEY,
    id_bill int,
    customer_name nvarchar(255) NOT NULL,
	employee_name nvarchar(255) NOT NULL,
    id_drink int,
    id_food int,
    count_food float default 0,
    count_drink float default 0

    FOREIGN KEY (id_bill) REFERENCES Bill(id_bill),
    FOREIGN KEY (id_drink) REFERENCES Drink(id_drink),
    FOREIGN KEY (id_food) REFERENCES Food(id_food)
)

GO
INSERT INTO Account(username,pass,role,name,phone,address,email,gioitinh,ngaysinh) values ('admin','1234567',0,'admin','0123456789','quan 10','admin@gmail.com',1,'10/10/2000')
GO
INSERT INTO TalbleFood(status) values
(0),
(0),
(0),
(0),
(0),
(0),
(0),
(0)
GO
INSERT INTO CategoriesFood(category) values (N'Nướng'),(N'Chiên'),(N'Lẩu')
GO
INSERT INTO Food(foodname,id_category,price) values
(N'Bò nướng',1,50000),
(N'Sườn nướng',1,50000),
(N'Cá nướng',1,50000),
(N'Cá chiên',2,50000),
(N'Gà chiên',2,50000),
(N'Cá viên chiên',2,50000),
(N'Lẩu thái',3,50000),
(N'Lẩu hải sản',3,50000),
(N'Lẩu bò',3,50000)

GO
INSERT INTO CategoriesDrink(category) values (N'Có gas'),(N'Bia'),(N'Rượu')
GO
INSERT INTO Food(foodname,id_category,price) values
(N'Sting',1,10000),
(N'7up',1,10000),
(N'Mirinda',1,10000),
(N'333',2,20000),
(N'Saigon',2,20000),
(N'Heineken',2,50000),
(N'Vodka',3,50000),
(N'Gạo',3,50000),
(N'Soju',3,50000)


