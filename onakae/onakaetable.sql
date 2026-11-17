CREATE TABLE IF NOT EXISTS rx_med_error_report 
  (
    id VARCHAR(100) PRIMARY KEY NOT NULL,
    me_report_date datetime,
    me_date  date,
    me_department  varchar(100),
    me_event  varchar(10),
    me_event_detail  varchar(10),
    me_drugname1  varchar(255),
    me_drugname2  varchar(255),
    me_icode1  varchar(100),
    me_icode2  varchar(100),
    me_level  varchar(10),
    me_solve  varchar(255),
    me_phar  varchar(100),
    updateflag  varchar(1) 
    );
	
CREATE TABLE IF NOT EXISTS rx_call_disp_pcsetchanel 
  (
    pcname VARCHAR(255) PRIMARY KEY NOT NULL,
    ipaddress  varchar(100),
    chanel  varchar(2),
    print_server  varchar(255),
    ptcall  varchar(1),
    displayscreen  varchar(1) 
    );
CREATE TABLE IF NOT EXISTS rx_call_ptname (
    hn  varchar(100) PRIMARY KEY NOT NULL,
    vn  varchar(100),
    calltime  datetime,
    ptname_call  varchar(255),
    ptname_tv_display  varchar(255),
    call_chanel  varchar(10),
    call1  varchar(1)
    );  
CREATE TABLE IF NOT EXISTS rx_disp_time (
    vn  varchar(100) PRIMARY KEY NOT NULL,
    hn  varchar(100),
    vstdate  date,
    vsttime  time,
    rxtime  time,
    cashiertime  time,
    stickertime  time,
    dispentime  time,
    calltime  time,
    pharmacy  varchar(255),
    updateflag  varchar(1)
    );  
CREATE TABLE IF NOT EXISTS rx_disp_report (
    vn  varchar(100) PRIMARY KEY NOT NULL,
    hn  varchar(100),
    vstdate  date,
    vsttime  time,
    rxtime  time,
    cashiertime  time,
    stickertime  time,
    dispentime  time,
    calltime  time,
    phar  varchar(255),
    updateflag  varchar(1)
    );  
CREATE TABLE IF NOT EXISTS rx_disp_report (
    vn  varchar(100) PRIMARY KEY NOT NULL,
    hn  varchar(100),
    vstdate  date,
    vsttime  time,
    rxtime  time,
    cashiertime  time,
    stickertime  time,
    dispentime  time,
    calltime  time,
    phar  varchar(255),
    updateflag  varchar(1)
    ); 

  CREATE TABLE IF NOT EXISTS rx_tv_display (
    hn  varchar(100) PRIMARY KEY NOT NULL,
    ptname  varchar(255),
    calltime time,
    chanel  varchar(100)
    ); 

  CREATE TABLE IF NOT EXISTS rx_youtube_video (
     video_id  text
    ); 

  CREATE TABLE IF NOT EXISTS rx_chanel_call (
     id  varchar(1) PRIMARY KEY NOT NULL,
     pcname varchar(255),
     chanel  varchar(100)
    ); 