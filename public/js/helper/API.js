async function getAllDataKaryawan() {
  try {
    const response = await $.ajax({
      url: `${url}/hrd/allDataKaryawan`,
      method: "GET",
      dataType: "json",
    });

    if (response.status === "success") {
      return response.data;
    } else {
      throw new Error(response.message || "Gagal ambil data");
    }
  } catch (err) {
    console.error("Gagal ambil data:", err.message);
    throw err;
  }
}

async function getAllDataKaryawanNew(url) {
  try {
    const response = await fetch(`${url}/hrd/allDataKaryawan`);
    const result = await response.json();

    if (result.status === "success") {
      return result.data;
    } else {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: `Terjadi kesalahan pada server.`,
      });
      return [];
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Gagal",
      text: `Terjadi kesalahan ${error.message}.`,
    });
    return [];
  }
}

async function getAllDataSumberInformasi(url) {
  try {
    const response = await $.ajax({
      url: `${url}/bukutamu/allDataSumberInformasi`,
      method: "GET",
      dataType: "json",
    });

    if (response.status === "success") {
      return response.data;
    } else {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: response.message || "Terjadi kesalahan saat mengambil data.",
      });
    }
  } catch (err) {
    Swal.fire({
      icon: "error",
      title: "Gagal",
      text:
        err.responseJSON?.message ||
        err.statusText ||
        "Terjadi kesalahan saat mengambil data.",
    });
    throw err;
  }
}

async function fetchDataSumberInformasiDetail(url) {
  try {
    const response = await fetch(
      `${url}/bukutamu/allDataSumberInformasiDetail`
    );
    const result = await response.json();

    if (result.status === "success") {
      return result.data;
    } else {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: `Terjadi kesalahan pada server.`,
      });
      return [];
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Gagal",
      text: `Terjadi kesalahan ${error.message}.`,
    });
    return [];
  }
}

async function getAllAlasanKunjunganBukuTamu(url) {
  try {
    const response = await fetch(
      `${url}/bukutamu/allDataAlasanKunjungan`
    );
    const result = await response.json();

    if (result.status === "success") {
      return result.data;
    } else {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: `Terjadi kesalahan pada server.`,
      });
      return [];
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Gagal",
      text: `Terjadi kesalahan ${error.message}.`,
    });
    return [];
  }
}


//POSISI KARYAWAN
async function getAllPosisiton(url) {
  try {
    const response = await fetch(`${url}/hrd/allDataPosisiton`);
    const result = await response.json();

    if (result.status === "success") {
      return result.data;
    } else {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: `Terjadi kesalahan pada server.`,
      });
      return [];
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Gagal",
      text: `Terjadi kesalahan ${error.message}.`,
    });
    return [];
  }
}

async function getAllSpvSales(url) {
  try {
    const response = await fetch(`${url}/sales/allDataSpvSales`);
    const result = await response.json();

    if (result.status === "success") {
      return result.data;
    } else {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: `Terjadi kesalahan pada server.`,
      });
      return [];
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Gagal",
      text: `Terjadi kesalahan ${error.message}.`,
    });
    return [];
  }
}

async function getAllSales(url) {
  try {
    const response = await fetch(`${url}/sales/allDataSales`);
    const result = await response.json();

    if (result.status === "success") {
      return result.data;
    } else {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: `Terjadi kesalahan pada server.`,
      });
      return [];
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Gagal",
      text: `Terjadi kesalahan ${error.message}.`,
    });
    return [];
  }
}

window.getAllDataKaryawan = getAllDataKaryawan;
window.getAllDataKaryawanNew = getAllDataKaryawanNew;
window.getAllDataSumberInformasi = getAllDataSumberInformasi;
window.fetchDataSumberInformasiDetail = fetchDataSumberInformasiDetail;
window.getAllAlasanKunjunganBukuTamu = getAllAlasanKunjunganBukuTamu;
window.getAllPosisiton = getAllPosisiton;
window.getAllSpvSales = getAllSpvSales;
window.getAllSales = getAllSales;
