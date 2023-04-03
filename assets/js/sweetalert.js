function status(id, folder) {
    $.ajax({
        type: "post",
        data: {
            'id': id
        },
        url: folder + '/cform/status',
        dataType: "json",
        success: function(data) {
            show(folder + '/cform', '#main');
        },
        error: function() {
            show(folder + '/cform', '#main');
        }
    });
}

function statuschange(folder, id, istatus, dfrom, dto) {
    if (id == '' || id == null) {
        swal("Maaf", "Data gagal diupdate :(", "error");
        return false;
    } else {
        if (istatus == '2') {
            teks = 'Dokumen Terkirim ke Atasan :)';
        } else if (istatus == '3') {
            teks = 'Change Request Dokumen :)';
        } else if (istatus == '4') {
            teks = 'Dokumen Telah Ditolak :)';
        } else if (istatus == '5') {
            teks = 'Dokumen Berhasil Dihapus :)';
        } else if (istatus == '6') {
            teks = 'Dokumen Telah Diapprove :)';
        } else if (istatus == '7') {
            teks = 'Dokumen Telah Dicancel :)';
        } else if (istatus == '9') {
            teks = 'Dokumen Telah Dibatalkan :)';
        } else if (istatus == '11') {
            teks = 'Dokumen Telah Terkirim :)';
        } else {
            teks = 'Dokumen Berhasil Diupdate :)';
        }

        if (istatus == '2') {
            teksx = 'Dokumen Gagal Dikirim ke Atasan :(';
        } else if (istatus == '3') {
            teksx = 'Gagal Change Request Dokumen :(';
        } else if (istatus == '4') {
            teksx = 'Dokumen Gagal Ditolak :(';
        } else if (istatus == '5') {
            teksx = 'Dokumen Gagal Dihapus :(';
        } else if (istatus == '6') {
            teksx = 'Dokumen Gagal Diapprove :(';
        } else if (istatus == '7') {
            teksx = 'Dokumen Gagal Dicancel :(';
        } else if (istatus == '9') {
            teksx = 'Dokumen Gagal Dibatalkan :(';
        } else if (istatus == '11') {
            teksx = 'Dokumen Gagal Terkirim :(';
        } else {
            teksx = 'Dokumen Gagal Diupdate :(';
        }
        $.ajax({
            type: "post",
            data: {
                'id': id,
                'istatus': istatus,
            },
            url: folder + '/cform/changestatus',
            dataType: "json",
            success: function(data) {
                if (data == true) {
                    swal({
                        title: "Berhasil!",
                        text: teks,
                        /*timer: 3000,*/
                        showConfirmButton: true,
                        type: "success",
                    }, function() {
                        show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                    });
                } else {
                    swal({
                        title: "Maaf!",
                        text: teksx,
                        showConfirmButton: true,
                        type: "error",
                    }, function() {
                        show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                    });
                }
            },
            error: function() {
                swal({
                    title: "Maaf!",
                    text: teksx,
                    showConfirmButton: true,
                    type: "error",
                }, function() {
                    show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                });
            }
        });
    }
}

function statuschangetransfer(folder, id, istatus, dfrom, dto) {
    if (id == '' || id == null) {
        swal("Maaf", "Data gagal diupdate :(", "error");
        return false;
    } else {
        if (istatus == '2') {
            teks = 'Dokumen Terkirim ke Atasan :)';
        } else if (istatus == '3') {
            teks = 'Change Request Dokumen :)';
        } else if (istatus == '4') {
            teks = 'Dokumen Telah Ditolak :)';
        } else if (istatus == '5') {
            teks = 'Dokumen Berhasil Dihapus :)';
        } else if (istatus == '6') {
            teks = 'Dokumen Telah Diapprove :)';
        } else if (istatus == '7') {
            teks = 'Dokumen Telah Dicancel :)';
        } else if (istatus == '9') {
            teks = 'Dokumen Telah Dibatalkan :)';
        } else if (istatus == '11') {
            teks = 'Dokumen Telah Terkirim :)';
        } else {
            teks = 'Dokumen Berhasil Diupdate :)';
        }

        if (istatus == '2') {
            teksx = 'Dokumen Gagal Dikirim ke Atasan :(';
        } else if (istatus == '3') {
            teksx = 'Gagal Change Request Dokumen :(';
        } else if (istatus == '4') {
            teksx = 'Dokumen Gagal Ditolak :(';
        } else if (istatus == '5') {
            teksx = 'Dokumen Gagal Dihapus :(';
        } else if (istatus == '6') {
            teksx = 'Dokumen Gagal Diapprove :(';
        } else if (istatus == '7') {
            teksx = 'Dokumen Gagal Dicancel :(';
        } else if (istatus == '9') {
            teksx = 'Dokumen Gagal Dibatalkan :(';
        } else if (istatus == '11') {
            teksx = 'Dokumen Gagal Terkirim :(';
        } else {
            teksx = 'Dokumen Gagal Diupdate :(';
        }
        $.ajax({
            type: "post",
            data: {
                'id': id,
                'istatus': istatus,
            },
            url: folder + '/cform/changestatustransfer',
            dataType: "json",
            success: function(data) {
                if (data == true) {
                    swal({
                        title: "Berhasil!",
                        text: teks,
                        /*timer: 3000,*/
                        showConfirmButton: true,
                        type: "success",
                    }, function() {
                        show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                    });
                } else {
                    swal({
                        title: "Maaf!",
                        text: teksx,
                        showConfirmButton: true,
                        type: "error",
                    }, function() {
                        show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                    });
                }
            },
            error: function() {
                swal({
                    title: "Maaf!",
                    text: teksx,
                    showConfirmButton: true,
                    type: "error",
                }, function() {
                    show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                });
            }
        });
    }
}

function statuschangearray(folder, id, istatus, dfrom, dto) {
    if (id == '' || id == null) {
        swal("Maaf", "Data gagal diupdate :(", "error");
        return false;
    } else {
        if (istatus == '2') {
            teks = 'Dokumen Terkirim ke Atasan :)';
        } else if (istatus == '3') {
            teks = 'Change Request Dokumen :)';
        } else if (istatus == '4') {
            teks = 'Dokumen Telah Ditolak :)';
        } else if (istatus == '5') {
            teks = 'Dokumen Berhasil Dihapus :)';
        } else if (istatus == '6') {
            teks = 'Dokumen Telah Diapprove :)';
        } else if (istatus == '7') {
            teks = 'Dokumen Telah Dicancel :)';
        } else if (istatus == '9') {
            teks = 'Dokumen Telah Dibatalkan :)';
        } else if (istatus == '11') {
            teks = 'Dokumen Telah Terkirim :)';
        } else {
            teks = 'Dokumen Berhasil Diupdate :)';
        }

        if (istatus == '2') {
            teksx = 'Dokumen Gagal Dikirim ke Atasan :(';
        } else if (istatus == '3') {
            teksx = 'Gagal Change Request Dokumen :(';
        } else if (istatus == '4') {
            teksx = 'Dokumen Gagal Ditolak :(';
        } else if (istatus == '5') {
            teksx = 'Dokumen Gagal Dihapus :(';
        } else if (istatus == '6') {
            teksx = 'Dokumen Gagal Diapprove :(';
        } else if (istatus == '7') {
            teksx = 'Dokumen Gagal Dicancel :(';
        } else if (istatus == '9') {
            teksx = 'Dokumen Gagal Dibatalkan :(';
        } else if (istatus == '11') {
            teksx = 'Dokumen Gagal Terkirim :(';
        } else {
            teksx = 'Dokumen Gagal Diupdate :(';
        }
        $.ajax({
            type: "post",
            data: {
                'id': id,
                'istatus': istatus,
            },
            url: folder + '/cform/changestatusarray',
            dataType: "json",
            success: function(data) {
                if (data == true) {
                    swal({
                        title: "Berhasil!",
                        text: teks,
                        /*timer: 3000,*/
                        showConfirmButton: true,
                        type: "success",
                    }, function() {
                        show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                    });
                } else {
                    swal({
                        title: "Maaf!",
                        text: teksx,
                        showConfirmButton: true,
                        type: "error",
                    }, function() {
                        show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                    });
                }
            },
            error: function() {
                swal({
                    title: "Maaf!",
                    text: teksx,
                    showConfirmButton: true,
                    type: "error",
                }, function() {
                    show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                });
            }
        });
    }
}

function statuschangex(folder, id, istatus, dfrom, dto) {
    if (id == '' || id == null) {
        swal("Maaf", "Data gagal diupdate :(", "error");
        return false;
    } else {
        if (istatus == '2') {
            teks = 'Dokumen Terkirim ke Atasan :)';
        } else if (istatus == '3') {
            teks = 'Change Request Dokumen :)';
        } else if (istatus == '4') {
            teks = 'Dokumen Telah Ditolak :)';
        } else if (istatus == '5') {
            teks = 'Dokumen Berhasil Dihapus :)';
        } else if (istatus == '6') {
            teks = 'Dokumen Telah Diapprove :)';
        } else if (istatus == '7') {
            teks = 'Dokumen Telah Dicancel :)';
        } else if (istatus == '9') {
            teks = 'Dokumen Telah Dibatalkan :)';
        } else if (istatus == '11') {
            teks = 'Dokumen Telah Terkirim :)';
        } else {
            teks = 'Dokumen Berhasil Diupdate :)';
        }

        if (istatus == '2') {
            teksx = 'Dokumen Gagal Dikirim ke Atasan :(';
        } else if (istatus == '3') {
            teksx = 'Gagal Change Request Dokumen :(';
        } else if (istatus == '4') {
            teksx = 'Dokumen Gagal Ditolak :(';
        } else if (istatus == '5') {
            teksx = 'Dokumen Gagal Dihapus :(';
        } else if (istatus == '6') {
            teksx = 'Dokumen Gagal Diapprove :(';
        } else if (istatus == '7') {
            teksx = 'Dokumen Gagal Dicancel :(';
        } else if (istatus == '9') {
            teksx = 'Dokumen Gagal Dibatalkan :(';
        } else if (istatus == '11') {
            teksx = 'Dokumen Gagal Terkirim :(';
        } else {
            teksx = 'Dokumen Gagal Diupdate :(';
        }
        $.ajax({
            type: "post",
            data: {
                'id': id,
                'istatus': istatus,
            },
            url: folder + '/cform/changestatusx',
            dataType: "json",
            success: function(data) {
                if (data == true) {
                    swal({
                        title: "Berhasil!",
                        text: teks,
                        /*timer: 3000,*/
                        showConfirmButton: true,
                        type: "success",
                    }, function() {
                        show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                    });
                } else {
                    swal({
                        title: "Maaf!",
                        text: teksx,
                        showConfirmButton: true,
                        type: "error",
                    }, function() {
                        show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                    });
                }
            },
            error: function() {
                swal({
                    title: "Maaf!",
                    text: teksx,
                    showConfirmButton: true,
                    type: "error",
                }, function() {
                    show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                });
            }
        });
    }
}

function changestatus(folder, id, istatus) {
    if (id == '' || id == null) {
        swal("Maaf", "Data gagal diupdate :(", "error");
        return false;
    } else {
        if (istatus == '2') {
            teks = 'Dokumen Terkirim ke Atasan';
        } else if (istatus == '3') {
            teks = 'Change Request Dokumen';
        } else if (istatus == '4') {
            teks = 'Dokumen Telah Ditolak';
        } else if (istatus == '6') {
            teks = 'Dokumen Telah Diapprove';
        } else if (istatus == '7') {
            teks = 'Dokumen Telah Dicancel';
        } else if (istatus == '9') {
            teks = 'Dokumen Telah Dibatalkan';
        } else {
            teks = 'Dokumen Berhasil Diupdate';
        }
        $.ajax({
            type: "post",
            data: {
                'id': id,
                'istatus': istatus
            },
            url: folder + '/cform/changestatus',
            dataType: "json",
            success: function(data) {
                swal({
                    title: "Berhasil!",
                    text: teks,
                    /*timer: 3000,   */
                    showConfirmButton: true,
                    type: "success",
                }, function() {
                    show(folder + '/cform', '#main');
                });
            },
            error: function() {
                show(folder + '/cform', '#main');
            }
        });
    }
}

function updatestatus1(controller, id, istatus) {
    swal({
        title: "Update Draft Ini?",
        text: "Anda tidak akan dapat memulihkan data ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Update!",
        cancelButtonText: "Tidak, batalkan!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: "post",
                data: {
                    'id': id,
                    'istatus': istatus,
                },
                url: controller + '/cform/updatestatus',
                dataType: "json",
                success: function(data) {
                    swal("Update!", "Data berhasil Diupdate :)", "success");
                    show(controller + '/cform', '#main');
                },
                error: function() {
                    swal("Maaf", "Data gagal diupdate :(", "error");
                }
            });
        } else {
            swal("Dibatalkan", "Anda membatalkan update :)", "error");
        }
    });
}

function updatestatus2(controller, id, id2, istatus) {
    swal({
        title: "Update Draft Ini?",
        text: "Anda tidak akan dapat memulihkan data ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Update!",
        cancelButtonText: "Tidak, batalkan!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: "post",
                data: {
                    'id': id,
                    'id2': id2,
                    'istatus': istatus,
                },
                url: controller + '/cform/updatestatus',
                dataType: "json",
                success: function(data) {
                    swal("Update!", "Data berhasil Diupdate :)", "success");
                    show(controller + '/cform', '#main');
                },
                error: function() {
                    swal("Maaf", "Data gagal diupdate :(", "error");
                }
            });
        } else {
            swal("Dibatalkan", "Anda membatalkan update :)", "error");
        }
    });
}

function cancel(controller, id, istatus) {
    swal({
        title: "Apakah anda yakin ?",
        text: "Anda tidak akan dapat memulihkan data ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Tidak, batalkan!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: "POST",
                data: {
                    'id': id,
                    'istatus': istatus
                },
                url: controller + '/cform/updatestatus',
                dataType: "json",
                success: function(data) {
                    swal("Dihapus!", "Data berhasil dihapus :)", "success");
                    show(controller + '/cform', '#main');
                },
                error: function() {
                    swal("Maaf", "Data gagal dihapus :(", "error");
                }
            });
        } else {
            swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
        }
    });
}

function batalkirim(controller, id, istatus) {
    swal({
        title: "Tarik Draft Dari Atasan?",
        text: "Anda tidak akan dapat memulihkan data ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Tarik!",
        cancelButtonText: "Tidak, batalkan!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: "post",
                data: {
                    'id': id,
                    'istatus': istatus,
                },
                url: controller + '/cform/updatestatus',
                dataType: "json",
                success: function(data) {
                    swal("Ditarik!", "Draft berhasil Ditarik Kemabali :)", "success");
                    show(controller + '/cform', '#main');
                },
                error: function() {
                    swal("Maaf", "Draft gagal ditarik :(", "error");
                }
            });
        } else {
            swal("Dibatalkan", "Anda membatalkan penarikan :)", "error");
        }
    });
}

function statuschangedebet(folder, id, istatus, dfrom, dto, no, idno) {
    if (id == '' || id == null) {
        swal("Maaf", "Data gagal diupdate :(", "error");
        return false;
    } else {
        if (istatus == '2') {
            teks = 'Dokumen Terkirim ke Atasan :)';
        } else if (istatus == '3') {
            teks = 'Change Request Dokumen :)';
        } else if (istatus == '4') {
            teks = 'Dokumen Telah Ditolak :)';
        } else if (istatus == '5') {
            teks = 'Dokumen Berhasil Dihapus :)';
        } else if (istatus == '6') {
            teks = 'Dokumen Telah Diapprove :)';
        } else if (istatus == '7') {
            teks = 'Dokumen Telah Dicancel :)';
        } else if (istatus == '9') {
            teks = 'Dokumen Telah Dibatalkan :)';
        } else if (istatus == '11') {
            teks = 'Dokumen Telah Terkirim :)';
        } else {
            teks = 'Dokumen Berhasil Diupdate :)';
        }

        if (istatus == '2') {
            teksx = 'Dokumen Gagal Dikirim ke Atasan :(';
        } else if (istatus == '3') {
            teksx = 'Gagal Change Request Dokumen :(';
        } else if (istatus == '4') {
            teksx = 'Dokumen Gagal Ditolak :(';
        } else if (istatus == '5') {
            teksx = 'Dokumen Gagal Dihapus :(';
        } else if (istatus == '6') {
            teksx = 'Dokumen Gagal Diapprove :(';
        } else if (istatus == '7') {
            teksx = 'Dokumen Gagal Dicancel :(';
        } else if (istatus == '9') {
            teksx = 'Dokumen Gagal Dibatalkan :(';
        } else if (istatus == '11') {
            teksx = 'Dokumen Gagal Terkirim :(';
        } else {
            teksx = 'Dokumen Gagal Diupdate :(';
        }
        $.ajax({
            type: "post",
            data: {
                'id': id,
                'istatus': istatus,
                'no': no,
                'idno': idno,
            },
            url: folder + '/cform/changestatus',
            dataType: "json",
            success: function(data) {
                if (data == true) {
                    swal({
                        title: "Berhasil!",
                        text: teks,
                        /*timer: 3000,*/
                        showConfirmButton: true,
                        type: "success",
                    }, function() {
                        show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                    });
                } else {
                    swal({
                        title: "Maaf!",
                        text: teksx,
                        showConfirmButton: true,
                        type: "error",
                    }, function() {
                        show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                    });
                }
            },
            error: function() {
                swal({
                    title: "Maaf!",
                    text: teksx,
                    showConfirmButton: true,
                    type: "error",
                }, function() {
                    show(folder + '/cform/index/' + dfrom + '/' + dto, '#main');
                });
            }
        });
    }
}