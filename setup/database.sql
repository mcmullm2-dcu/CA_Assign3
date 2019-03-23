create database assign3;
grant all on assign3.* to michael@localhost identified by 'dcu';
use assign3;

create table user (
  id int not null auto_increment,
  email varchar(320) not null unique,
  password_hash varchar(255) not null,
  name varchar(100) not null,
  constraint user_pk primary key (id)
);

create table role (
  id int not null auto_increment,
  name varchar(50) not null unique,
  constraint role_pk primary key (id)
);

create table user_role (
  user_id int not null,
  role_id int not null,
  constraint user_role_pk primary key (user_id, role_id),
  constraint fk_user_id foreign key (user_id) references user(id),
  constraint fk_role_id foreign key (role_id) references role(id)
);

create table customer (
  code varchar(32) not null,
  name varchar(64) not null,
  account_manager int,
  constraint customer_pk primary key (code),
  constraint fk_customer_account_manager foreign key (account_manager) references user(id)
);

create table job (
  job_no varchar(32) not null,
  customer_code varchar(32) not null,
  title varchar(64),
  deadline datetime,
  complete bit not null default 0,
  constraint job_pk primary key (job_no),
  constraint fk_customer_code foreign key (customer_code) references customer(code)
);

create table process (
  id int not null auto_increment,
  name varchar(100) not null,
  active bit,
  constraint process_pk primary key (id)
);

create table label (
  id int not null auto_increment,
  name varchar(50),
  constraint label_pk primary key (id)
);

create table process_label (
  process_id int not null,
  label_id int not null,
  constraint process_label_pk primary key (process_id, label_id),
  constraint fk_process_label_process foreign key (process_id) references process (id),
  constraint fk_process_label_label foreign key (label_id) references label(id)
);

create table availability (
  id int not null auto_increment,
  process_id int not null,
  day_of_week tinyint unsigned not null,
  start_at time not null,
  end_at time not null,
  constraint availability_pk primary key (id),
  constraint fk_availability_process foreign key (process_id) references process (id)
);

create table workflow (
  id int not null auto_increment,
  name varchar(50) not null,
  description text not null,
  constraint workflow_pk primary key (id)
);

create table workflow_process (
  workflow_id int not null,
  process_id int not null,
  sequence int not null,
  estimated_time int unsigned not null, -- in seconds
  constraint workflow_process_pk primary key (workflow_id, process_id, sequence),
  constraint fk_workflow_process_workflow foreign key (workflow_id) references workflow (id),
  constraint fk_workflow_process_process foreign key (process_id) references process (id)
);

create table process_role (
  process_id int not null,
  role_id int not null,
  constraint process_role_pk primary key (process_id, role_id),
  constraint fk_process_role_process foreign key (process_id) references process (id),
  constraint fk_process_role_role foreign key (role_id) references role (id)
);

create table job_schedule (
  job_no varchar(32) not null,
  sequence int unsigned not null,
  process_id int not null,
  scheduled_start datetime,
  scheduled_end datetime,
  actual_start datetime,
  actual_end datetime,
  is_complete bit not null default 0,
  constraint job_schedule_pk primary key (job_no, sequence),
  constraint fk_job_schedule_job_no foreign key (job_no) references job (job_no),
  constraint fk_job_schedule_process foreign key (process_id) references process (id)
);

create table dashboard (
  id int not null auto_increment,
  name varchar(32) not null,
  description text not null,
  url varchar(100) not null unique,
  constraint dashboard_pk primary key (id)
);

create table dashboard_roles (
  dashboard_id int not null,
  role_id int not null,
  constraint fk_dashboard_roles_dashboard foreign key (dashboard_id) references dashboard (id),
  constraint fk_dashboard_roles_role foreign key (role_id) references role (id)
);

-- =======================================================================
-- Role Data
-- =======================================================================
insert into role (name) values ('Admin');
insert into role (name) values ('Structural Department');
insert into role (name) values ('Design Department');
insert into role (name) values ('Prepress Operators');
insert into role (name) values ('Printer A Operators');
insert into role (name) values ('Printer B Operators');
insert into role (name) values ('Small Format Printer Operators');
insert into role (name) values ('Finishing Department');
insert into role (name) values ('Kitting Department');
insert into role (name) values ('Despatch');
insert into role (name) values ('Account Manager');

-- =======================================================================
-- User Data
-- =======================================================================
insert into user (email, password_hash, name) values ('admin@example.com', '$2y$10$aY2HePuoeGjKjcZZ0M0jjuwUuojWhEfz9yOXooyYa710QQtrapcBG', 'Administrator');
insert into user (email, password_hash, name) values ('structural@example.com', '$2y$10$.KEoRzbIiS2Dr9cXJ9bmIuKf82f9Zc8VzED/YD9U/pTtve3pyoDsO', 'Structural Engineer');
insert into user (email, password_hash, name) values ('design@example.com', '$2y$10$cdFbkX/sQ8IAf52Rc3mTk.MmTWiEu1WWCmtAbGCqGlyj8.J9M.R1C', 'Graphic Designer');
insert into user (email, password_hash, name) values ('prepress1@example.com', '$2y$10$CXxj8rugkA3vMneMH6KsxenmJuU/qYqpIs.WW0S8LVibwh5iytgBC', 'Prepress Operator 1');
insert into user (email, password_hash, name) values ('prepress2@example.com', '$2y$10$s2ivULjPv3AGflkEoHOTIOTEl8lbV8VKdxlvmRBxh52B8o1yPR4i.', 'Prepress Operator 2');
insert into user (email, password_hash, name) values ('printera@example.com', '$2y$10$.YQ5IDOS/lOOZqyEhyYBouSIMxezTn47cRq6/m.GQWcQKtNRn349O', 'Printer A Operator');
insert into user (email, password_hash, name) values ('printerb@example.com', '$2y$10$FRXVTnLTZn6XVG8k6uOhl.YiYHpSui1oSOlvwBkNRsPmHWuqTycp2', 'Printer B Operator');
insert into user (email, password_hash, name) values ('finishing@example.com', '$2y$10$i61lT08cXfZbHbe7C6Y7Re2XaTbgaCT6uxtDPhSIm6O.xHuXWv6VC', 'Finishing Staff');
insert into user (email, password_hash, name) values ('kitting@example.com', '$2y$10$9PI.kBk91h2UiSrsr6sOE.l4LoFnD0vbtAJjaYYUlWIMBUnY3YYb6', 'Kitting Staff');
insert into user (email, password_hash, name) values ('despatch@example.com', '$2y$10$mzE5daPacz1FVl5iNPwYGOi51xsxfeAYcQh4WcRCy0ShtmFpGC1yK', 'Despatch Manager');
insert into user (email, password_hash, name) values ('sales@example.com', '$2y$10$cIRRvKPCgXPSu3YkyaCoOeBHpK4xM6vs65sCpfZ1l6rQsDt6A3ubK', 'Sales Manager');

-- =======================================================================
-- User Role Data
-- =======================================================================
insert into user_role (user_id, role_id) values (1,1);
insert into user_role (user_id, role_id) values (2,2);
insert into user_role (user_id, role_id) values (3,3);
insert into user_role (user_id, role_id) values (4,4);
insert into user_role (user_id, role_id) values (5,4);
insert into user_role (user_id, role_id) values (6,5);
insert into user_role (user_id, role_id) values (7,6);
insert into user_role (user_id, role_id) values (8,8);
insert into user_role (user_id, role_id) values (9,9);
insert into user_role (user_id, role_id) values (10,10);
insert into user_role (user_id, role_id) values (11,11);

-- =======================================================================
-- Dashboard Data
-- =======================================================================
insert into dashboard (name, description, url) values ('Admin Panel', 'General administration utilities.', 'admin.php');
insert into dashboard (name, description, url) values ('Tasks', 'View and update scheduled tasks.', 'tasks.php');
insert into dashboard (name, description, url) values ('My Accounts', 'View the current status of my customer jobs.', 'customerjobs.php');
insert into dashboard (name, description, url) values ('Customers', 'Add or edit customer data.', 'customers.php');
insert into dashboard (name, description, url) values ('Processes', 'Add or edit production processes.', 'processes.php');
insert into dashboard (name, description, url) values ('Workflows', 'View production workflows.', 'workflows.php');
insert into dashboard (name, description, url) values ('Jobs', 'Add, edit or cancel jobs.', 'jobs.php');
insert into dashboard (name, description, url) values ('Schedule', 'Schedule jobs.', 'schedule.php');

-- =======================================================================
-- Dashboard Role Data
-- =======================================================================
insert into dashboard_roles (dashboard_id, role_id) values (1,1);
insert into dashboard_roles (dashboard_id, role_id) values (2,1);
insert into dashboard_roles (dashboard_id, role_id) values (3,1);
insert into dashboard_roles (dashboard_id, role_id) values (4,1);
insert into dashboard_roles (dashboard_id, role_id) values (5,1);
insert into dashboard_roles (dashboard_id, role_id) values (6,1);
insert into dashboard_roles (dashboard_id, role_id) values (7,1);
insert into dashboard_roles (dashboard_id, role_id) values (8,1);

insert into dashboard_roles (dashboard_id, role_id) values (2,2);
insert into dashboard_roles (dashboard_id, role_id) values (2,3);
insert into dashboard_roles (dashboard_id, role_id) values (2,4);
insert into dashboard_roles (dashboard_id, role_id) values (2,5);
insert into dashboard_roles (dashboard_id, role_id) values (2,6);
insert into dashboard_roles (dashboard_id, role_id) values (2,7);
insert into dashboard_roles (dashboard_id, role_id) values (2,8);
insert into dashboard_roles (dashboard_id, role_id) values (2,9);
insert into dashboard_roles (dashboard_id, role_id) values (2,10);

insert into dashboard_roles (dashboard_id, role_id) values (3,11);
insert into dashboard_roles (dashboard_id, role_id) values (4,11);
insert into dashboard_roles (dashboard_id, role_id) values (7,11);
insert into dashboard_roles (dashboard_id, role_id) values (8,11);

-- =======================================================================
-- Customer Data
-- =======================================================================
insert into customer (code, name, account_manager) values ('DABF01', 'Dabfeed', 11);
insert into customer (code, name, account_manager) values ('AILA01', 'Ailane', null);
insert into customer (code, name, account_manager) values ('MITA01', 'Mita', 11);
insert into customer (code, name, account_manager) values ('BUBB01', 'Bubblemix', 11);
insert into customer (code, name, account_manager) values ('ROOM01', 'Roombo', null);
insert into customer (code, name, account_manager) values ('FLIP01', 'Flipopia', null);
insert into customer (code, name, account_manager) values ('PIXO01', 'Pixoboo', 11);
insert into customer (code, name, account_manager) values ('BUBB02', 'Bubblebox', null);
insert into customer (code, name, account_manager) values ('FEED01', 'Feedfire', 11);
insert into customer (code, name, account_manager) values ('REAL01', 'Realmix', null);
insert into customer (code, name, account_manager) values ('BABB01', 'Babbleblab', 11);
insert into customer (code, name, account_manager) values ('YAKI01', 'Yakijo', null);
insert into customer (code, name, account_manager) values ('VINT01', 'Vinte', 11);
insert into customer (code, name, account_manager) values ('SKYN01', 'Skyndu', null);
insert into customer (code, name, account_manager) values ('OYOP01', 'Oyope', 11);
insert into customer (code, name, account_manager) values ('MYBU01', 'Mybuzz', 11);
insert into customer (code, name, account_manager) values ('YAMI01', 'Yamia', null);
insert into customer (code, name, account_manager) values ('YOUS01', 'Youspan', null);
insert into customer (code, name, account_manager) values ('JETW01', 'Jetwire', 11);
insert into customer (code, name, account_manager) values ('YOUT01', 'Youtags', 11);

-- =======================================================================
-- Job Data
-- =======================================================================
insert into job (job_no, customer_code, title, deadline, complete) values ('264211', 'BUBB02', 'Launch Posters', '2019-04-05', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264212', 'MITA01', 'Stationery', '2019-05-14', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264213', 'YAKI01', 'Display Units', '2019-04-07', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264214', 'ROOM01', 'Exhibition Graphics', '2019-04-20', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264215', 'VINT01', 'Shelf Signage', '2019-06-19', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264216', 'MYBU01', 'Business Cards', '2019-05-07', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264217', 'VINT01', 'Letterheads', '2019-05-27', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264218', 'BABB01', 'Vehicle Wraps', '2019-05-04', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264219', 'AILA01', 'Box stickers', '2019-06-18', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264220', 'REAL01', 'Cutouts', '2019-04-07', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264221', 'YOUT01', '8x4 Corriboard Signs', '2019-05-02', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264222', 'SKYN01', 'FSDU White Sample', '2019-05-05', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264223', 'MITA01', '48-sheet Billboards', '2019-05-01', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264224', 'YAKI01', 'Mesh Banner', '2019-06-05', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264225', 'ROOM01', 'Sales Brochures', '2019-05-11', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264226', 'MITA01', '30x40 Posters', '2019-05-21', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264227', 'AILA01', '96-sheet Billboards', '2019-06-03', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264228', 'BABB01', 'Folded business cards', '2019-06-04', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264229', 'YAKI01', 'Bunting for Summer Fair', '2019-04-08', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264230', 'YOUS01', 'Election Posters', '2019-05-18', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264231', 'PIXO01', 'Black vinyl lettering', '2019-04-22', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264232', 'FEED01', 'Rebranded Stationery', '2019-06-26', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264233', 'FEED01', 'A1 Strutted Boards', '2019-04-10', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264234', 'AILA01', '60x40 posters', '2019-04-15', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264235', 'FEED01', 'Personalised Invites', '2019-06-13', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264236', 'PIXO01', 'Numbered Tickets', '2019-05-30', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264237', 'YOUS01', 'Launch Menu', '2019-05-15', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264238', 'PIXO01', 'Tom''s Business Cards', '2019-05-19', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264239', 'OYOP01', 'Kiss-cut vinyl logo', '2019-05-24', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264240', 'SKYN01', 'Window Vinyls (outside fix)', '2019-05-10', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264241', 'DABF01', 'Golden Square Billboard', '2019-06-22', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264242', 'YAKI01', 'Unit Headers', '2019-06-26', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264243', 'SKYN01', 'Price Tags (Euro)', '2019-04-21', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264244', 'MITA01', 'CCTV diabond signage', '2019-06-22', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264245', 'JETW01', 'Toblerone Unit', '2019-04-27', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264246', 'DABF01', 'UK Business Cards', '2019-04-24', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264247', 'PIXO01', 'Bollard Covers', '2019-04-23', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264248', 'ROOM01', 'A4 Menus', '2019-05-28', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264249', 'BABB01', '30cm Cubes', '2019-05-18', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264250', 'YOUS01', 'Twist and Lock Units', '2019-06-05', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264251', 'AILA01', 'DL Leaflets', '2019-04-07', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264252', 'FEED01', 'Promotional Cut outs', '2019-05-09', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264253', 'JETW01', 'Assembly Sheets', '2019-06-18', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264254', 'VINT01', 'Tourist Posters', '2019-05-24', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264255', 'SKYN01', 'Price Tags (Sterling)', '2019-05-09', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264256', 'BUBB02', 'Pizza Box Sample', '2019-04-07', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264257', 'VINT01', 'Calendars', '2019-06-05', true);
insert into job (job_no, customer_code, title, deadline, complete) values ('264258', 'BUBB02', 'Certificates', '2019-06-22', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264259', 'YOUT01', 'Bus Shelter Posters', '2019-06-29', false);
insert into job (job_no, customer_code, title, deadline, complete) values ('264260', 'YAKI01', 'Window vinyls (inside fix)', '2019-04-15', true);

-- =======================================================================
-- Processes
-- =======================================================================
insert into process (name, active) values ('Design', true);
insert into process (name, active) values ('Structural', true);
insert into process (name, active) values ('Prepress', true);
insert into process (name, active) values ('LF Printer A', true);
insert into process (name, active) values ('LF Printer B', true);
insert into process (name, active) values ('SF Printer', true);
insert into process (name, active) values ('Finishing', true);
insert into process (name, active) values ('Kitting', true);
insert into process (name, active) values ('Despatch', true);

-- =======================================================================
-- Labels
-- =======================================================================
insert into label (name) values ('Graphic Design');
insert into label (name) values ('Printer');
insert into label (name) values ('Large Format Printer');

-- =======================================================================
-- Process Labels
-- =======================================================================
insert into process_label (process_id, label_id) values (1, 1);
insert into process_label (process_id, label_id) values (3, 1);
insert into process_label (process_id, label_id) values (4, 2);
insert into process_label (process_id, label_id) values (5, 2);
insert into process_label (process_id, label_id) values (6, 2);
insert into process_label (process_id, label_id) values (4, 3);
insert into process_label (process_id, label_id) values (5, 3);

-- =======================================================================
-- Process Roles
-- =======================================================================
insert into process_role (process_id, role_id) values (1,1);
insert into process_role (process_id, role_id) values (2,1);
insert into process_role (process_id, role_id) values (3,1);
insert into process_role (process_id, role_id) values (4,1);
insert into process_role (process_id, role_id) values (5,1);
insert into process_role (process_id, role_id) values (6,1);
insert into process_role (process_id, role_id) values (7,1);
insert into process_role (process_id, role_id) values (8,1);
insert into process_role (process_id, role_id) values (9,1);

insert into process_role (process_id, role_id) values (1,3);
insert into process_role (process_id, role_id) values (2,2);
insert into process_role (process_id, role_id) values (3,4);
insert into process_role (process_id, role_id) values (4,5);
insert into process_role (process_id, role_id) values (5,6);
insert into process_role (process_id, role_id) values (6,7);
insert into process_role (process_id, role_id) values (7,8);
insert into process_role (process_id, role_id) values (8,9);
insert into process_role (process_id, role_id) values (9,10);

-- =======================================================================
-- Availability
-- =======================================================================
-- Design
insert into availability (process_id, day_of_week, start_at, end_at) values (1, 1, '09:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (1, 2, '09:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (1, 3, '09:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (1, 4, '09:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (1, 5, '09:00:00', '17:00:00');
-- Structural
insert into availability (process_id, day_of_week, start_at, end_at) values (2, 1, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (2, 2, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (2, 3, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (2, 4, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (2, 5, '09:00:00', '18:00:00');
-- Prepress
insert into availability (process_id, day_of_week, start_at, end_at) values (3, 1, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (3, 2, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (3, 3, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (3, 4, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (3, 5, '09:00:00', '18:00:00');
-- LF Printer A
insert into availability (process_id, day_of_week, start_at, end_at) values (4, 1, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (4, 2, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (4, 3, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (4, 4, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (4, 5, '09:00:00', '18:00:00');
-- LF Printer B
insert into availability (process_id, day_of_week, start_at, end_at) values (5, 1, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (5, 2, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (5, 3, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (5, 4, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (5, 5, '08:00:00', '17:00:00');
-- SF Printer
insert into availability (process_id, day_of_week, start_at, end_at) values (6, 1, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (6, 2, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (6, 3, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (6, 4, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (6, 5, '08:00:00', '17:00:00');
-- Finishing
insert into availability (process_id, day_of_week, start_at, end_at) values (7, 1, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (7, 2, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (7, 3, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (7, 4, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (7, 5, '07:00:00', '16:00:00');
-- Kitting
insert into availability (process_id, day_of_week, start_at, end_at) values (8, 1, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (8, 2, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (8, 3, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (8, 4, '08:00:00', '17:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (8, 5, '07:00:00', '16:00:00');
-- Despatch
insert into availability (process_id, day_of_week, start_at, end_at) values (9, 1, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (9, 2, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (9, 3, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (9, 4, '09:00:00', '18:00:00');
insert into availability (process_id, day_of_week, start_at, end_at) values (9, 5, '09:00:00', '18:00:00');

-- =======================================================================
-- Workflows
-- =======================================================================
insert into workflow (name, description) values ('Small Format (general)', 'Used for processing standard small-format printing jobs.');
insert into workflow (name, description) values ('Large Format A', 'Used for processing standard large-format printing jobs using Printer A.');
insert into workflow (name, description) values ('Large Format B', 'Used for processing standard large-format printing jobs using Printer B.');
insert into workflow (name, description) values ('Free-Standing Units (artwork supplied)', 'Used for processing customer-supplied artwork for creating FSDUs.');
insert into workflow (name, description) values ('Free-Standing Units (no artwork supplied)', 'Used for processing FSDUs where no customer artwork has been supplied.');

-- =======================================================================
-- Workflow Processes
-- =======================================================================
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (1, 3, 0, 300);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (1, 6, 1, 600);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (1, 7, 2, 600);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (1, 8, 3, 300);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (1, 9, 4, 300);

insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (2, 3, 0, 600);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (2, 4, 1, 1800);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (2, 7, 2, 900);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (2, 8, 3, 600);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (2, 9, 4, 300);

insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (3, 3, 0, 600);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (3, 5, 1, 1800);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (3, 7, 2, 900);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (3, 8, 3, 600);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (3, 9, 4, 300);

insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (4, 2, 0, 900);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (4, 3, 1, 1200);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (4, 4, 2, 3600);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (4, 7, 3, 7200);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (4, 8, 4, 3600);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (4, 9, 5, 300);

insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (5, 2, 0, 7200);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (5, 1, 1, 7200);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (5, 3, 2, 1200);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (5, 4, 3, 3600);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (5, 7, 4, 7200);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (5, 8, 5, 3600);
insert into workflow_process (workflow_id, process_id, sequence, estimated_time) values (5, 9, 6, 300);

