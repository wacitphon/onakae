let usrtext = 'ผู้ป่วยที่มารับยากรุณาแสดงบัตรประชาชนด้วยนะคะ ขอบคุณค่ะ';
let currentLanguage = 'th'; // กำหนดภาษาเริ่มต้น
document.addEventListener('DOMContentLoaded', function () {


    var fullname = localStorage.getItem('fullname');
    var chanel = localStorage.getItem('chanel');
    $('#txtErrprDate').val(getCurrentDate());
    var ptcall = localStorage.getItem('ptcall');

    if (ptcall) {
        $('#txtPtcall').val(ptcall);

    }

    // var pcal = $('#txtPtcall').val();
    // if (pcal == '1') {

    //     dispalptcall();
    //     setInterval(function () {
    //         dispalptcall();
    //     }, 5000);
    // }

    if (fullname && chanel) {

        $('#fullname').text(fullname);
        $('#txtChanel').val(chanel);

    } else {
        window.location.href = 'login.html';
    }

    $("#txtSEARCH").focus();
    var print_server = localStorage.getItem('print_server');
    // alert(print_server);
    getPrintServer(print_server);
    cleariframe();

    // toggleMicrophone();

});



$(document).ready(function () {

    $('#printserver').on('change', function () {
        setPrintServer();
    });


    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            if ($('#formmederror').is(':visible')) {

                $('#formmederror').hide();
                $('#txtSEARCH').focus();

            } else
                if ($('#formeditusage').is(':visible')) {
                    $('#txtLine1').val('');
                    $('#txtLine2').val('');
                    $('#txtLine3').val('');
                    $('#txtCode').text('');
                    $('#txtDrugUsage').val('');
                    $('#txtSp_Use').val('');
                    $('#txtHos_Guid').val('');
                    $('#txtshortlist').val('');
                    $('#formeditusage').hide();
                    $('#txtSEARCH').focus();

                } else {
                    clearscreen();
                }



        } else if (event.ctrlKey && event.key === 'Enter') {

            event.preventDefault();

            var element = document.getElementById("fname");

            if (element) {

                // callptname(element.innerHTML);
                // displayptname();
                save_call_pt()

            } else {

                console.log("ไม่พบ element ที่มี id='fname'");
            }
        } else if (event.ctrlKey && event.shiftKey) {

            event.preventDefault();
            var element = document.getElementById("fname");

            if (element) {
                // displayptname()
                save_call_pt();
            } else {

                console.log("ไม่พบ element ที่มี id='fname'");
            }
        } else if (event.ctrlKey && event.altKey) {
            event.preventDefault();
            callptadmit();
        } else if (event.ctrlKey && event.key === ' ') {
            event.preventDefault();
            userdefinetextcall();
        }
        else if (event.ctrlKey && event.key === 'ArrowLeft') {

            event.preventDefault();
            var element = document.getElementById("btnSaveDisp");
            if (element) {
                element.click();

            } else {

                console.log("ไม่พบ element ที่มี id='btnSaveDisp'");
            }
        } else if (event.ctrlKey && event.key === 'ArrowRight') {

            event.preventDefault();
            var element = document.getElementById("btnSaveDisp");
            if (element) {
                element.click();

            } else {

                console.log("ไม่พบ element ที่มี id='btnSaveDisp'");
            }
        } else if (event.ctrlKey && event.key === 'ArrowDown') {

            event.preventDefault();
            var element = document.getElementById("btnSaveDisp");
            if (element) {
                element.click();

            } else {

                console.log("ไม่พบ element ที่มี id='btnSaveDisp'");
            }
        }

    });


    $('#txtUsername').on('keypress', function (event) {
        if (event.which === 13) {
            // event.preventDefault();
            $('#txtPassword').focus();
            $('#txtPassword').select();
            // alert($(this).val());
            // alert(pwd);
            // alert(chanel);
        }
    });

    $('#txtPassword').on('keypress', function (event) {
        if (event.which === 13) {

            // event.preventDefault();
            $('#Chanelname').focus();
            $('#Chanelname').select();
            // alert($(this).val());
        }
    });

    $('#Chanelname').on('keypress', function (event) {
        if (event.which === 13) {
            checkuserlogin();

        }
    });




    $('#txtSEARCH').on('keypress', function (event) {
        if (event.which === 13) {
            // getIPAddress();
            event.preventDefault();

            var searchText = $(this).val().trim();
            if (searchText != '') {
                dodisplaydata(searchText)
                $(this).val('');
            } else {
                alert('คำค้นหาห้ามว่าง');
                $(this).focus()
            }


            // var encodedText = encodeURIComponent(searchText);
            // var url = 'callpt.php?text=' + encodedText;  
            // $('#resultFrame').attr('src', url);
        }
    });


    $('#txtErrprDate').on('keypress', function (event) {
        if (event.which === 13) {

            event.preventDefault();
            $('#txtErrprDep').focus();
            $('#txtErrprDep').select();
            // alert($(this).val());
        }
    });

    $('#txtErrprDep').on('keypress', function (event) {
        if (event.which === 13) {

            event.preventDefault();
            $('#txtErrorEvent').focus();
            $('#txtErrorEvent').select();
            // alert($(this).val());
        }
    });

    $('#txtErrorEvent').on('keypress', function (event) {
        if (event.which === 13) {

            event.preventDefault();
            $('#txtErrorDetail').focus();
            $('#txtErrorDetail').select();
            // alert($(this).val());
        }
    });

    $('#txtErrorDetail').on('keypress', function (event) {
        if (event.which === 13) {

            event.preventDefault();
            $('#txtErrorItems1').focus();
            $('#txtErrorItems1').select();
            // alert($(this).val());
        }
    });

    $('#txtErrorItems1').on('keypress', function (event) {
        if (event.which === 13) {

            event.preventDefault();
            $('#txtErrorItems2').focus();
            $('#txtErrorItems2').select();
            // alert($(this).val());
        }
    });

    $('#txtErrorItems2').on('keypress', function (event) {
        if (event.which === 13) {

            event.preventDefault();
            $('#txtErrorLevel').focus();
            $('#txtErrorLevel').select();
            // alert($(this).val());
        }
    });

    $('#txtErrorLevel').on('keypress', function (event) {
        if (event.which === 13) {

            event.preventDefault();
            $('#txtSolve').focus();
            $('#txtSolve').select();
            // alert($(this).val());
        }
    });

    $('#txtSolve').on('keypress', function (event) {
        if (event.which === 13) {

            event.preventDefault();
            savemederror()
        }
    });





    $('#btnHome').on('click', function () {
        // Swal.fire({
        //     // position: 'top-end',
        //     background: 'rgba(0,0,0,0.20)',
        //     icon: 'success',
        //     title: 'เรียกชื่อให้แล้ว',
        //     showConfirmButton: false,
        //     timer: 1000
        //   })
        // openAndRefresh('callpt.html')
        // playcall('ขนส่ง');
        clearscreen();
    });

    $('#login').on('click', function () {

        // checkuserlogin();
        var username = $('#txtUsername').val();
        var password = $('#txtPassword').val();
        var chanelname = $('#Chanelname').val();
        alert(username + ' ' + password + ' ' + chanelname)

    });

    $("#txtErrorItems1").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "items.php",
                dataType: "json",
                data: {
                    term: request.term.trim()
                },
                success: function (data) {
                    var filteredData = data.slice(0, 10);
                    response(filteredData);
                },
                error: function () {
                    response([]);
                }
            });
        },
        autoFocus: true,
        select: function (event, ui) {
            event.preventDefault();
            var selectedValue = ui.item.value;
            $(this).val('');
            if (selectedValue !== "") {
                const itemsdata = selectedValue.split("|");
                var itemsname = itemsdata[0].trim();
                var icode = itemsdata[1].trim();
                $("#txtIcode1").val(icode);
                $(this).val(itemsname);
                $("#txtErrorItems2").focus();
                $("#txtErrorItems2").select()

            } else {
                // แสดงข้อความแจ้งเตือนว่ารายการเวชภัณฑ์ห้ามว่าง
                // alert('xxxxx');
            }
        }



    });

    $("#txtErrorItems2").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "items.php",
                dataType: "json",
                data: {
                    term: request.term.trim()
                },
                success: function (data) {
                    var filteredData = data.slice(0, 10);
                    response(filteredData);
                },
                error: function () {
                    response([]);
                }
            });
        },
        autoFocus: true,
        select: function (event, ui) {
            event.preventDefault();
            var selectedValue = ui.item.value;
            $(this).val('');
            if (selectedValue !== "") {
                const itemsdata = selectedValue.split("|");
                var itemsname = itemsdata[0].trim();
                var icode = itemsdata[1].trim();
                $("#txtIcode2").val(icode);
                $(this).val(itemsname);
                $("#txtErrorLevel").focus();
                $("#txtErrorLevel").select()

            } else {
                // แสดงข้อความแจ้งเตือนว่ารายการเวชภัณฑ์ห้ามว่าง
                // alert('xxxxx');
            }
        }



    });

    $("#txtshortlist").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "getusage.php",
                dataType: "json",
                data: {
                    term: request.term.trim()
                },
                success: function (data) {
                    var filteredData = data.slice(0, 10);
                    response(filteredData);
                },
                error: function () {
                    response([]);
                }
            });
        },
        autoFocus: true,
        select: function (event, ui) {
            event.preventDefault();
            var selectedValue = ui.item.value;
            $(this).val('');
            if (selectedValue !== "") {
                const itemsdata = selectedValue.split("|");
                var shortlist = itemsdata[0].trim();
                var name1 = itemsdata[1].trim();
                var name2 = itemsdata[2].trim();
                var name3 = itemsdata[3].trim();
                var drugusage = itemsdata[4].trim();

                $('#txtCode').text(shortlist);
                $('#txtLine1').val(name1);
                $('#txtLine2').val(name2);
                $('#txtLine3').val(name3);
                $('#txtDrugUsage').val(drugusage);
                $('#txtSp_Use').val('');
            } else {
                // แสดงข้อความแจ้งเตือนว่ารายการเวชภัณฑ์ห้ามว่าง
                // alert('xxxxx');
            }
        }
    });


    document.getElementById('btnPrint').addEventListener('click', function () {
        // รายการของ checkbox ที่ถูกเลือก
        var selectedCheckboxes = document.querySelectorAll('input[name="chk[]"]:checked');
        var selectedIcodes = Array.from(selectedCheckboxes).map(function (checkbox) {
            return checkbox.value;
        });

        // ส่งข้อมูลไปยังไฟล์ sticker_print_data.php ผ่านการเรียกใช้งาน XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'sticker_print_data.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // ดำเนินการเพิ่มเติมหากต้องการ
            }
        };
        xhr.send('icodes=' + encodeURIComponent(selectedIcodes.join(',')));
    });





});


////////////////////////////////////////////////////


function printSticker(vn) {
    // alert(vn)
    var selectedCheckboxes = document.querySelectorAll('input[name="chk[]"]:checked');
    var selectedIcodes = Array.from(selectedCheckboxes).map(function (checkbox) {
        return checkbox.value;
    });

    var printServer = $('#printserver').val();
    var chk = selectedIcodes.join(',');

    // var words = chk.split(','); // แยกคำออกมาเป็น array

    // for (var i = 0; i < words.length; i++) {
    //     alert(words[i]); // แสดงค่าใน chk ตามลำดับของ index
    // }



    $.ajax({
        url: 'sticker_print_data.php',
        method: 'POST',
        data: { chk: chk, print_server: printServer, vn: vn }, // ปรับเปลี่ยนชื่อตัวแปรเป็น camelCase
        success: function (data) {
            if (data.length > 0) {
                alert('Print to Print server!!');
            }
        },
        error: function (xhr, status, error) {
            console.error(error); // แสดงข้อผิดพลาดในการเรียก ajax ถ้ามี
        }
    });
}



function dodisplaydata(searchText) {

    if (searchText != '') {
        var call_chanel = $('#txtChanel').val();

        $.ajax({
            url: 'tvdelete.php',
            method: 'POST', // เปลี่ยน method เป็น 'POST'
            data: { chanel: call_chanel },
            success: function (data) {

            }
        });

        $.ajax({
            url: 'opdvisit.php',
            method: 'POST', // เปลี่ยน method เป็น 'POST'
            data: { hn: searchText, chanel: call_chanel },
            success: function (data) {
                if (data.length > 0) {
                    // var call_chanel = $('#txtChanel').val();
                    $('#ovst').html('');
                    $('#ovst').html(data);
                    cleariframe();
                    calculateAndDisplaySum();
                } else {
                    // alert('0')
                    $.ajax({
                        url: 'getipd.php',
                        method: 'POST',
                        data: { hn: searchText, chanel: call_chanel },
                        success: function (data) {
                            // alert('ไม่พบข้อมูลที่ค้นหา');
                            if (data.length > 0) {
                                // alert(data);

                                $('#ovst').html('');
                                $('#ovst').html(data);
                                cleariframe();
                            } else {
                                // alert('ไม่พบข้อมูลที่ค้นหา')
                                $.ajax({
                                    url: 'getptname.php',
                                    method: 'GET',
                                    data: { hn: searchText, chanel: call_chanel },
                                    success: function (data) {
                                        if (data.length > 0) {
                                            // alert('ไม่พบข้อมูลการรับยาในวันนี้');
                                            $('#ovst').html('');
                                            $('#ovst').html(data);

                                            // Get the current date and time in the Thailand time zone
                                            const options = { timeZone: 'Asia/Bangkok', timeStyle: 'medium' };
                                            const currentTimeInThailand = new Date().toLocaleString('en-US', options);

                                            // Display the current time in the Thailand time zone
                                            console.log(`Current time in Thailand: ${currentTimeInThailand}`);

                                            cleariframe();
                                        } else {
                                            alert('ไม่พบข้อมูลที่ค้นหา')
                                        }
                                    }
                                });
                            }




                        }
                    });
                }




            }
        });
    } else {
        alert('คำค้นหาห้ามว่าง');

    }

    $(this).focus()
    // return false;
}



function callptname(text) {
    if (text != '') {
        var chanel = $('#txtChanel').val();
        if (chanel == '') {
            alert('กำหนดช่องจ่ายยาก่อนเรียกชื่อรับยา');
            $('#txtChanel').focus();
        } else {
            var calltext = 'ขอเชิญคุณ ' + text + ' ติดต่อช่องรับยา ' +
                chanel;
            // playcall(calltext);
            var encodedText = encodeURIComponent(calltext);
            // var url ='https://khon.sirinthornhospital.go.th/ptcall/callpt.php?text=' + encodedText;
            var url = 'callpt.php?text=' + encodedText;
            Swal.fire({
                // position: 'top-end',
                background: 'rgba(0,0,0,0.20)',
                icon: 'success',
                title: 'เรียกชื่อให้แล้ว',
                showConfirmButton: false,
                timer: 1000
            })
            var url = 'ptcall.php?text=' + encodedText;
            $('#resultFrame').attr('src', url);
            // $.ajax({
            //     url: 'https://khonrx.com/ptcall/callpt.php',
            //     method: 'POST', // เปลี่ยน method เป็น 'POST'
            //     data: {text: encodedText},
            //     success: function (data) {
            //         Swal.fire({
            //             // position: 'top-end',
            //             background: 'rgba(0,0,0,0.20)',
            //             icon: 'success',
            //             title: 'เรียกชื่อให้แล้ว',
            //             showConfirmButton: false,
            //             timer: 1000
            //         });

            //         $('#resultFrame').attr('src', url);
            //     }

            // });


        }
    }

    $("#txtSEARCH").focus();
}


// function callptname(text) {
//     var chanel = $('#txtChanel').val();

//     if (chanel == '') {
//         alert('กำหนดช่องจ่ายยาก่อนเรียกชื่อรับยา');
//         $('#txtChanel').focus();
//     } else {
//         var calltext = 'ขอเชิญคุณ ' + text + ' ติดต่อช่องรับยา ' + chanel;
//         var encodedText = encodeURIComponent(calltext);
//         var url = 'callpt.php?text=' + encodedText;

//         // เรียก PHP ผ่าน AJAX เพื่อเล่นเสียง
//         $.get(url, function(data) {
//             // หากการเรียก PHP เสร็จสิ้น ให้เรียกใช้งานเสียงดังนี้
//             $('#resultFrame').attr('src', url);

//             // หรือหยุดรอเป็นเวลา 5 วินาที
//             // setTimeout(function() {
//                 $("#txtSEARCH").focus();
//             // }, 5000);
//         });
//     }
// }


function getchanel() {

    $.ajax({
        url: 'getchanel.php',
        method: 'POST', // เปลี่ยน method เป็น 'POST'
        // data: { hn: searchText },
        success: function (data) {
            // console.log(data)
            $('#Chanelname').val(data[0].chanel.trim());
            // cleariframe();

        }
    });

}

function getPrintServer(print_server) {
    $.ajax({
        url: 'getprint_server_name.php',
        method: 'GET', // เปลี่ยนเป็น GET หรือ POST ตามที่ server รอรับ
        dataType: 'json', // ระบุว่าคาดหวังข้อมูลเป็น JSON
        success: function (data) {
            if (data && data.length > 0) {
                var printServerSelect = $('#printserver');
                printServerSelect.empty(); // ล้างตัวเลือกเดิมทั้งหมด

                // เพิ่มตัวเลือกใหม่จากข้อมูลที่ได้รับ
                for (var i = 0; i < data.length; i++) {
                    printServerSelect.append($('<option>', {
                        value: data[i].server_name,
                        text: data[i].server_name
                    }));
                }


                if (print_server) {
                    // ใช้ .val() เพื่อกำหนดค่าให้กับ select element
                    printServerSelect.val(print_server);
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching print server data:', error);
        }
    });
}


function setPrintServer() {
    var print_server = $('#printserver').val(); // แก้ไขให้ใช้ตัวแปร print_server แทน printserver
    $.ajax({
        url: 'setprintserver.php',
        method: 'POST',
        data: { print_server: print_server },
        success: function (data) {
            $('#Chanelname').val(data[0].chanel.trim());
        },
        error: function (xhr, status, error) {
            console.log("An error occurred: " + error); // แสดงข้อความ error ใน console สำหรับการแก้ปัญหา
        }
    });
}


function checkuserlogin() {

    var usr = $('#txtUsername').val().trim();
    var pwd = $('#txtPassword').val().trim();
    var chanel = $('#Chanelname').val().trim();

    if (usr == '') {
        alert('Username ห้ามว่าง');
        $('#txtUsername').focus();
    } else if (pwd == '') {
        alert('Password ห้ามว่าง');
        $('#txtPassword').focus();
    } else if (chanel == '') {
        alert('ช่องจ่ายยาห้ามว่าง');
        $('#Chanelname').focus();
    } else {
        $.ajax({
            url: 'getlogin.php',
            method: 'GET',
            data: { username: usr, password: pwd, chanel: chanel },
            success: function (data) {
                // Assuming that the response from getlogin.php is a JSON object
                if (data.length > 0) {
                    // User logged in successfully
                    $("#mainform").show();
                    $("#menu").hide();
                    // alert('OK');
                } else {
                    // Login failed
                    alert('เข้าสู่ระบบไม่สำเร็จ');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        });
    }
}

function clearscreen() {

    $('#txtLine1').val('');
    $('#txtLine2').val('');
    $('#txtLine3').val('');
    $('#txtCode').val('');
    $('#txtDrugUsage').val('');
    $('#txtSp_Use').val('');
    $('#txtHos_Guid').val('');
    $('#txtshortlist').val('');
    $('#formeditusage').hide();
    $('#ovst').html('');
    $("#txtSEARCH").focus();
    var call_chanel = $('#txtChanel').val();
    $.ajax({
        url: 'tvdelete.php',
        method: 'POST', // เปลี่ยน method เป็น 'POST'
        data: { chanel: call_chanel },
        success: function (data) {

        }
    });

}

function savedispen(data_disptime) {

    var vn = data_disptime[0];
    var hn = data_disptime[1];
    var vstdate = data_disptime[2];
    var vsttime = data_disptime[3];
    var rxtime = data_disptime[4];
    var cashiertime = data_disptime[5];
    var stickertime = data_disptime[6];
    var calltime = data_disptime[7];
    // alert(calltime);
    var dispentime = data_disptime[8];
    var pharmacy = localStorage.getItem('fullname');
    // alert(pharmacy);
    // var pharmacy = $('#fullname').text();

    $.ajax({
        url: 'updatedisp.php',
        method: 'POST',
        data: {
            vn: vn,
            hn: hn,
            vstdate: vstdate,
            vsttime: vsttime,
            rxtime: rxtime,
            cashiertime: cashiertime,
            stickertime: stickertime,
            calltime: calltime,
            dispentime: dispentime,
            pharmacy: pharmacy
        },
        success: function (data) {
            if (data.length > 0) {
                clearscreen();
                $("#txtSEARCH").focus();


            } else {
                // บันทึกไม่สำเร็จ
                alert('บันทึกไม่สำเร็จ');
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
            alert('เกิดข้อผิดพลาดโปรดติดต่อใครล่ะ');
        }
    });
}



function cleariframe() {
    var iframe = document.getElementById('resultFrame');
    iframe.src = '';
}

function logout() {
    var iframe = document.getElementById('resultFrame');
    iframe.src = '';
    localStorage.removeItem('fullname');
    localStorage.removeItem('chanel');
    clearscreen();
    window.location.href = 'login.html';


}

function getdrugallergy(hn) {

    $.ajax({
        url: 'getdrugallergy.php',
        method: 'POST',
        data: { hn: hn },
        success: function (data) {
            // Assuming that the response from getlogin.php is a JSON object
            if (data.length > 0) {

                alert(data[0].agent + ' : ' + data[0].symptom);
            } else {
                // Login failed
                // alert('เข้าสู่ระบบไม่สำเร็จ');
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        }
    });

}


function displayovst() {
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    }
    // document.getElementById("formstockcard").style.display = "block";

}

function logout() {
    localStorage.removeItem('fullname');
    localStorage.removeItem('chanel');
    localStorage.removeItem('print_server');
    window.location.href = 'login.html';
}


function textToAudio(t) {
    // let msg = document.getElementById("text-to-speech").value;

    let speech = new SpeechSynthesisUtterance();
    speech.lang = "th-TH";
    speech.text = t;
    speech.volume = 1;
    speech.rate = 0.5;
    speech.pitch = 1;
    window.speechSynthesis.speak(speech);
}


function displayformdrugusage(line_json) {

    var shortlist = line_json[0];
    var line1 = line_json[1];
    var line2 = line_json[2];
    var line3 = line_json[3];
    var drugusage = line_json[4];
    var sp_use = line_json[5];
    var hos_guid = line_json[6];

    $('#txtLine1').val('');
    $('#txtLine2').val('');
    $('#txtLine3').val('');
    $('#txtCode').text('');
    $('#txtDrugUsage').val('');
    $('#txtSp_Use').val('');
    $('#txtHos_Guid').val('');
    $('#txtshortlist').val('');

    $('#txtLine1').val(line1);
    $('#txtLine2').val(line2);
    $('#txtLine3').val(line3);
    $('#txtCode').text(shortlist);
    $('#txtDrugUsage').val(drugusage);
    $('#txtSp_Use').val(sp_use);
    $('#txtHos_Guid').val(hos_guid);
    // $('#txtshortlist').val(shortlist);
    document.getElementById("formeditusage").style.display = "block";
    $('#txtshortlist').focus();
    $('#txtshortlist').select();

}

function cloaseformdrugusage() {
    $('#txtLine1').val('');
    $('#txtLine2').val('');
    $('#txtLine3').val('');
    $('#txtCode').text('');
    $('#txtDrugUsage').val('');
    $('#txtSp_Use').val('');
    $('#txtHos_Guid').val('');
    $('#txtshortlist').val('');
    document.getElementById("formeditusage").style.display = "none";
    // $('#txtdistributor').focus();

}

function displayformmederrore() {
    $('#txtErrprDate').val(getCurrentDate());
    // document.getElementById('txtErrprDate').value = getCurrentDate();
    // $('#txtErrprDep').val('');
    $('#txtErrorEvent').val('0');
    updateTxtErrorDetail('0');
    // $('#txtErrorDetail').val('');
    $('#txtErrorItems1').val('');
    $('#txtErrorItems2').val('');
    $('#txtErrorLevel').val('');
    $('#txtSolve').val('');

    document.getElementById("formmederror").style.display = "block";
    $('#txtErrprDate').focus();
    $('#txtErrprDate').select();

}
function cloaseformmederror() {
    document.getElementById("formmederror").style.display = "none";
}


function calculateAndDisplaySum() {
    var sum = 0;
    var priceCells = document.querySelectorAll('.sumprice'); // เลือกเซลล์ที่มีคลาส "sumprice"

    // วน loop ผ่านเซลล์ทุกเซลล์ที่มีคลาส "sumprice" และดึงค่าราคาเพิ่มลงใน sum
    for (var i = 0; i < priceCells.length; i++) {
        var price = parseFloat(priceCells[i].textContent);
        if (!isNaN(price)) {
            sum += price;
        }
    }

    // กำหนดค่าผลรวมในเซลล์ที่มี id "txtSumprice"
    var sumPriceCell = document.getElementById('txtSumprice');
    if (sumPriceCell) {
        sumPriceCell.textContent = sum.toFixed(2); // แสดงผลรวมเป็นทศนิยม 2 ตำแหน่ง
    }
}

// เรียกใช้ฟังก์ชันคำนวณและแสดงผลรวมเมื่อโหลดหน้าเว็บ
// window.addEventListener('load', calculateAndDisplaySum);



function updateptdrugusage() {

    var sp_use = $('#txtSp_Use').val();
    var drugusage = $('#txtDrugUsage').val();
    var hos_guid = $('#txtHos_Guid').val();
    var hn = $('#lblHn');
    if (sp_use == '') {
        $.ajax({
            url: 'updateptdrugusage.php',
            method: 'POST',
            data: { hos_guid: hos_guid, drugusage: drugusage },
            success: function (data) {
                // Assuming that the response from getlogin.php is a JSON object
                if (data.length > 0) {

                    // var shortlist = $('#txtCode').text(); // ใช้ .html() แทน .text()
                    // var shortlistCell = document.getElementById('shortlist_' + hos_guid);
                    // shortlistCell.innerHTML = shortlist;
                    // var drugusagecell = document.getElementById('drugusage_' + hos_guid);
                    // drugusagecell.innerHTML = drugusage;
                    // var sp_usecell = document.getElementById('sp_use_' + hos_guid);
                    // sp_usecell.innerHTML = sp_use;
                    cloaseformdrugusage();
                    if (hn) {
                        dodisplaydata(hn.text());
                    }

                    $("#txtSEARCH").focus();

                } else {
                    // Login failed
                    // alert('เข้าสู่ระบบไม่สำเร็จ');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        });
    } else {

        alert('กำหนดเอง');

    }



}


function displayptname() {

    var ptname_call = $('#fname').text();
    var ptname_tv_display = $('#fname').text();
    var call_chanel = $('#txtChanel').val();
    var hn = $('#lblHn').text();
    var vn = $('#lblVN').text();

    var ptcall1 = $('#txtPtcall').val();
    if (ptcall1 == '1') {

        callptname(ptname_call);
    } else {
        $.ajax({
            url: "display_ptname.php",
            method: "POST",
            data: {
                ptname_call: ptname_call,
                ptname_tv_display: ptname_tv_display,
                call_chanel: call_chanel,
                hn: hn,
                vn: vn,
                call1: '1'
            },
            success: function (data) {
                if (data.length > 0) {
                    Swal.fire({
                        // position: 'top-end',
                        background: 'rgba(0,0,0,0.20)',
                        icon: 'success',
                        title: 'เรียกชื่อให้แล้ว',
                        showConfirmButton: false,
                        timer: 1000
                    })

                } else {
                    // $('#dataTableCall tbody').html('');
                    console.log('ไม่พบข้อมูล');
                }
            },
            error: function () {
                console.log('เกิดข้อผิดพลาดในการรับข้อมูล');
            }
        });

    }
};


function openAndRefresh(newUrl) {

    $.ajax({
        url: newUrl, // เปลี่ยน URL ให้เป็น URL ของข้อมูลที่คุณต้องการโหลด
        type: "GET", // หรือ "POST" ตามที่เหมาะสม
        dataType: "html", // หรือ "json" หากข้อมูลเป็น JSON
        success: function (data) {
            // นำข้อมูลที่โหลดมาแสดงบนหน้าเว็บ
            $("#dataContainer").html(data);
        },
        error: function () {
            // แสดงข้อความข้อผิดพลาดหากการโหลดข้อมูลล้มเหลว
            $("#dataContainer").html("เกิดข้อผิดพลาดในการโหลดข้อมูล");
        },
    });
}

function save_call_pt() {

    var lang=isEnglishOrThai( $('#fname').text());
// alert(lang)
    if(lang==='en'){
        var callmsg = $('#enptname').text() +
        ' Please Contact Channel  ' + $('#txtChanel').val();
        lang='en';

    } else {
        var callmsg = 'ขอเชิญคุณ ' + $('#fname').text() +
        ' ติดต่อช่องรับยา ' + $('#txtChanel').val();
        lang='th';
    }

    Swal.fire({
        background: 'rgba(0,0,0,0.20)',
        icon: 'success',
        title: 'เรียกชื่อให้แล้ว',
        showConfirmButton: false,
        timer: 1000
    })
    $.ajax({
        method: 'POST',
        data: { callmsg: callmsg,lang:lang },
        url: "save_call_pt.php",
        success: function (data) {

        },
        error: function () {
            console.log('เกิดข้อผิดพลาดในการรับข้อมูล');
        }
    });

}

function dispalptcall() {

    var value = $('#pttocall1').val();

    if (value === '') {
        $.ajax({
            url: "getpttocall.php",
            success: function (data) {
                if (data.length > 0) {

                    // setTimeout(function () {
                    $('#pttocall1').val(data[0].ptname_call);
                    $('#chanel1').val(data[0].call_chanel);
                    $('#call1').val(data[0].call1);
                    var text = $('#pttocall1').val();
                    var hn = data[0].hn;

                    callptname1(text);
                    $.ajax({
                        method: 'POST',
                        data: { hn: hn },
                        url: "delete_rx_call_ptname.php",
                        success: function (data) {

                            $('#pttocall1').val('');
                            $('#chanel1').val('');
                            $('#call1').val('');
                        },
                        error: function () {
                            console.log('เกิดข้อผิดพลาดในการรับข้อมูล');
                        }
                    });

                } else {

                }
            },
            error: function () {
                console.log('เกิดข้อผิดพลาดในการรับข้อมูล');
            }
        });

    } else {
        setTimeout(function () {
            $('#pttocall1').val('');
            $('#chanel1').val('');
        }, 1000);

    }


}

function callptname1(text) {
    var chanel = $('#chanel1').val();
    var call_ = $('#call1').val();
    if (text != '') {
        $.ajax({
            method: 'POST',
            data: { ptcall: '2' },
            url: "rx_call_check_insert.php",
            success: function (data) {
                if (call_ == '1') {
                    var calltext = 'รับยาแอดมืทคุณ ' + text;
                } else {
                    var calltext = 'ขอเชิญคุณ ' + text + ' ติดต่อช่องรับยา ' +
                        chanel;
                }

                var encodedText = encodeURIComponent(calltext);
                // var url = '้https://khonrx.com/ptcall/callpt.php?text=' + encodedText;
                var url = 'ptcall.php?text=' + encodedText;
                $('#resultFrame1').attr('src', url);
            }
        });

        // }
    }


}


async function toggleMicrophone() {

    const pcall = $('#txtPtcall').val();
    // var pcall = ptcall1;
    var microphoneIcon = document.getElementById("microphone-icon");
    Swal.fire({
        title: 'การตั้งค่าเชื่อมต่อเครื่องเสียง \nต้องการให้เครื่องนี้เป็นเครื่องสำหรับออกเสียงเรียกรับยา?',
        showDenyButton: true,
        // showCancelButton: true,
        confirmButtonText: 'ต้องการ',
        denyButtonText: `ไม่ต้องการ`,
    }).then((result) => {

        if (result.isConfirmed) {
            Swal.fire('Saved!', '', 'success')
            pcall = '1';
            microphoneIcon.className = "fa fa-microphone";
            $('#txtPtcall').val('1');

        } else if (result.isDenied) {
            pcall = '0';
            microphoneIcon.className = "fa fa-microphone-slash";
            $('#txtPtcall').val('0');
            //   Swal.fire('Changes are not saved', '', 'info')
        }
    })


    $.ajax({
        method: 'POST',
        data: { ptcall: pcall },
        url: "updateptcall.php",
        success: function (data) {
            // window.location = window.location.href;
        }
    });

}

function updatemainmi() {

    Swal.fire({
        title: 'Do you want to save the changes?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Save',
        denyButtonText: `Don't save`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            Swal.fire('Saved!', '', 'success')
            $.ajax({
                method: 'POST',
                data: { ptcall: pcall },
                url: "updateptcall.php",
                success: function (data) {
                    // window.location = window.location.href;
                }
            });
        } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
        }
    })

}

function callptadmit() {
    var pcall = $('#fname').text();
    if (pcall.length > 0) {
        var callmsg = 'รับยาแอดมิทคุณ ' + pcall;

        Swal.fire({
            background: 'rgba(0,0,0,0.20)',
            icon: 'success',
            title: 'เรียกชื่อให้แล้ว',
            showConfirmButton: false,
            timer: 1000
        })
        $.ajax({
            method: 'POST',
            data: { callmsg: callmsg,lang:'th' },
            url: "save_call_pt.php",
            success: function (data) {

            },
            error: function () {
                console.log('เกิดข้อผิดพลาดในการรับข้อมูล');
            }
        });
    }

    // var pcall = $('#fname');
    // var ptcall_ = $('#txtPtcall').val();
    // if (pcall.length > 0) {
    //     var calltext = 'รับยาแอดมิทคุณ ' + pcall.text();
    //     var encodedText = encodeURIComponent(calltext);
    //     var url = 'callpt.php?text=' + encodedText;
    //     Swal.fire({
    //         background: 'rgba(0,0,0,0.20)',
    //         icon: 'success',
    //         title: 'เรียกชื่อให้แล้ว',
    //         showConfirmButton: false,
    //         timer: 1000
    //     })
    //     if (ptcall_ == '1') {
    //         $('#resultFrame').attr('src', url);
    //     } else {
    //         $.ajax({
    //             url: "display_ptname.php",
    //             method: "POST",
    //             data: {
    //                 ptname_call: pcall,
    //                 ptname_tv_display: pcall,
    //                 call_chanel: call_chanel,
    //                 hn: hn,
    //                 vn: vn,
    //                 call1: '1'
    //             },
    //             success: function (data) {
    //                 if (data.length > 0) {
    //                     // Swal.fire({
    //                     //     // position: 'top-end',
    //                     //     background: 'rgba(0,0,0,0.20)',
    //                     //     icon: 'success',
    //                     //     title: 'เรียกชื่อให้แล้ว',
    //                     //     showConfirmButton: false,
    //                     //     timer: 1000
    //                     // })

    //                 } else {
    //                     // $('#dataTableCall tbody').html('');
    //                     console.log('ไม่พบข้อมูล');
    //                 }
    //             },
    //             error: function () {
    //                 console.log('เกิดข้อผิดพลาดในการรับข้อมูล');
    //             }
    //         });

    //     }


    // }
}



function updateTxtErrorDetail(selectedValue) {
    // alert(selectedValue);
    var txtErrorDetail = $('#txtErrorDetail');//document.getElementById('txtErrorDetail');
    if (selectedValue === "0") {

        txtErrorDetail.html(`
            <option value=""></option>
        `);
    } else if (selectedValue === "1") {
        txtErrorDetail.html(`
            <option value="A1">การสั่งใช้ยาโดยไม่มีข้อบ่งชี้/มีข้อห้ามใช้/ สั่งใช้ยาไม่ตรงกับการวินิจฉัยโรค</option>
            <option value="A2">การสั่งใช้ยาที่ผู้ป่วยมีประวัติแพ้ยา</option>
            <option value="A3">การสั่งใช้ยาในขนาดมาก/น้อยเกินไป</option>
            <option value="A4">สั่งใช้ยาที่มีปฏิกิริยาต่อกันหรือกับยาเดิม ของผู้ป่วย</option>
            <option value="A5">เขียนชื่อ/ขนาด/ความแรง/รูปแบบยา/ ปริมาณยา/วิธีการใช้/ความถี่/สั่งยาไม่ ครบถ้วน/คลาดเคลื่อน</option>
            <option value="A6">สั่งใช้ยาซ้ำซ้อน/ซ้ำรายการ</option>
            <option value="A7">สั่งใช้ยาไม่ตรงกับผู้ป่วย</option>
        `);
    } else if (selectedValue === "2") {
        txtErrorDetail.html(`
            <option value="B1">จัดยาผิดชนิด/ผิดขนาด/ความแรง</option>
            <option value="B2">จัดยาผิดรูปแบบ</option>
            <option value="B3">จัดยาผิดปริมาณ (น้อย/เกิน)</option>
            <option value="B4">จัดยาไม่ครบรายการ/เกินรายการ/จัดยาที่ ไม่ได้สั่ง</option>
            <option value="B5">จัดยาที่หมดอายุ/เสื่อมสภาพ</option
            <option value="B6">พิมพ์ฉลากคลาดเคลื่อน (ผิดชนิด/ตัว/ ขนาด/วิธีใช้/พิมพ์ไม่ครบ)</option>
            <option value="B7">ติดฉลากยาผิด/สลับยา</option>
            <option value="B8">ระบุชนิด/rate/ปริมาณ IV ที่ต้องการเบิก คลาดเคลื่อน/ไม่ระบุ</option>
            <option value="B9">คัดลอกในรายการ order คลาดเคลื่อน/ ไม่ครบ/เกินรายการ</option>
        `);
    } else if (selectedValue === "3") {
        txtErrorDetail.html(`
            <option value="C1">จ่ายยาผิดคน</option>
            <option value="C2">จ่ายยาผิดชนิด/ความแรง/ขนาด</option>
            <option value="C3">จ่ายยาผิดรูปแบบ</option>
            <option value="C4">จ่ายยาผิดปริมาณ (น้อย/เกิน)</option>
            <option value="C5">จ่ายยาที่ติดฉลากยาผิด/สลับยา/ ฉลากคลาดเคลื่อน</option
            <option value="C6">จ่ายยาไม่ครบรายการ/เกินรายการ</option>
            <option value="C7">ไม่ให้คำแนะนำในการใช้ยาที่ เหมาะสม</option>
            <option value="C8">จ่ายยาหมดอายุ/ เสื่อมสภาพ</option>       
        `);
    } else if (selectedValue === "4") {
        txtErrorDetail.html(`
            <option value="D1">ให้ยาผู้ป่วยผิดคน</option>
            <option value="D2">ให้ยาไม่ครบ</option>
            <option value="D3">ให้ยาผิดชนิด / ขนาด / รูปแบบ</option>
            <option value="D4">ให้ยาผิด Route / ตำแหน่ง</option>
            <option value="D5">ให้ยาในอัตราเร็วที่ไม่ถูกต้อง</option
            <option value="D6">ให้ยาที่ผู้สั่งใช้ยาไม่ได้สั่ง</option>
            <option value="D7">ให้ยาในจำนวนครั้งมากกว่าที่สั่ง</option>
            <option value="D8">ให้ยาผิดเทคนิค / เตรียมยาก่อนให้คลาดเคลื่อน</option>
            <option value="D9">ให้ยาผิดเวลา</option>
            <option value="D10">ลงลายมือชื่อให้ยาคลาดเคลื่อน (ไม่ลง ลายมือชื่อ/ไม่ real time หรือลงชื่อ ล่วงหน้า)</option>
        `);
    }
    // else {
    //     // ค่าเริ่มต้นหรือค่าไม่ตรงกับเงื่อนไขใดๆ
    //     // กำหนดให้ txtErrorDetail ใช้ค่าเริ่มต้น
    //     // txtErrorDetail.html(initialOptions;
    // }
}

function savemederror() {

    var me_date = $('#txtErrprDate').val();
    var me_dep = $('#txtErrprDep').val();
    var me_event = $('#txtErrorEvent').val();
    var me_event_detail = $('#txtErrorDetail').val();
    var me_items1 = $('#txtErrorItems1').val();
    var me_items2 = $('#txtErrorItems2').val();
    var me_level = $('#txtErrorLevel').val();
    var me_solve = $('#txtSolve').val();

    if (me_event === '') {
        alert('เหตุการณ์ ห้ามว่าง');
        $('#txtErrorEvent').focus();
        $('#txtErrorEvent').select();
        return 0;

    } else {
        if (me_event_detail === '') {
            alert('รายละเอียดเหตุการณ์ ห้ามว่าง');
            $('#txtErrorDetail').focus();
            $('#txtErrorDetail').select();
            return 0;
        } else {

            $.ajax({
                url: 'save_mederror.php',
                method: 'POST',
                data: {
                    me_date: me_date,
                    me_department: me_dep,
                    me_event: me_event,
                    me_event_detail: me_event_detail,
                    me_drugname1: me_items1,
                    me_drugname2: me_items2,
                    me_level: me_level,
                    me_solve: me_solve
                },
                success: function (data) {
                    alert('บันทึกสำเร็จ');
                    // รีเซ็ตค่าฟอร์มหรือทำอื่นตามที่ต้องการ
                    $('#txtErrprDate').val(getCurrentDate());
                    // $('#txtErrprDep').val('');
                    $('#txtErrorEvent').val('0');
                    updateTxtErrorDetail('0');
                    $('#txtErrorItems1').val('');
                    $('#txtErrorItems2').val('');
                    $('#txtErrorLevel').val('');
                    $('#txtSolve').val('');
                    $('#txtErrprDate').focus();
                    $('#txtErrprDate').select();

                }
            });

        }
    }





}
function clearformmederror() {
    $('#txtErrprDate').val(getCurrentDate());
    // $('#txtErrprDep').val('');
    $('#txtErrorEvent').val('0');
    updateTxtErrorDetail('0');
    $('#txtErrorItems1').val('');
    $('#txtErrorItems2').val('');
    $('#txtIcode1').val('');
    $('#txtIcode2').val('');

    $('#txtErrorLevel').val('');
    $('#txtSolve').val('');
    $('#txtErrprDate').focus();
    $('#txtErrprDate').select();
}

function getCurrentDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Add 1 to month because it's zero-indexed
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}


async function userdefinetextcall() {
    const { value: text } = await Swal.fire({
        input: 'textarea',
        inputLabel: 'Message',
        inputValue: usrtext,
        inputPlaceholder: 'Type your message here...',
        inputAttributes: {
            'aria-label': 'Type your message here'
        },
        showCancelButton: true,
        inputValidator: (value) => {
            if (!value) {
                return 'You need to write something!';
            } else if (value.length > 200) {
                // Truncate the text to 255 characters
                const truncatedText = value.slice(0, 200);
                // Set the textarea value to the truncated text
                Swal.getInput().value = truncatedText;
                return 'Text has been truncated to 200 characters';
            }
        }
    });
    if (text) {
        usrtext = text;
        // var callmsg = text ;
        Swal.fire({
            background: 'rgba(0,0,0,0.20)',
            icon: 'success',
            title: 'เรียกชื่อให้แล้ว',
            showConfirmButton: false,
            timer: 1000
        })
        $.ajax({
            method: 'POST',
            data: { callmsg: text,lang:'th' },
            url: "save_call_pt.php",
            success: function (data) {

            },
            error: function () {
                console.log('เกิดข้อผิดพลาดในการรับข้อมูล');
            }
        });
    }
}


function isEnglishOrThai(str) {
    // ใช้ Regex เพื่อตรวจสอบว่า string มีภาษาอังกฤษหรือภาษาไทย
    var regex = /^[a-zA-Z]+$/; // ตรวจสอบว่าเป็นอักษรอังกฤษ
    var thaiRegex = /^[ก-๏\s]+$/; // ตรวจสอบว่าเป็นอักษรไทย
  
    if (regex.test(str.substring(0, 5))) {
      return "en";
    } else if (thaiRegex.test(str.substring(0, 5))) {
      return "th";
    } else {
      return "th";
    }
  }
  
//   // ตัวอย่างการใช้งาน
//   var input = "Hello World"; // หรือ "สวัสดี โลก"
//   var result = isEnglishOrThai(input);
  
//   console.log(result); // ผลลัพธ์จะแสดง "English" หรือ "Thai" ตามที่เหมาะสม
  