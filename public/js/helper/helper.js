function formatNumberIDR(number) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 2,
  }).format(number);
}

function makeUppercase(text) {
  return text && typeof text === "string" ? text.toUpperCase() : "";
}

function parseRp(text) {
  return parseFloat(text.replace(/Rp|\./g, "").trim()) || 0;
}

function capitalizeEachWord(str) {
  return str.toLowerCase().replace(/\b\w/g, function (char) {
    return char.toUpperCase();
  });
}

function validateInput(value, message) {
  if (!value) {
    Swal.fire({
      icon: "warning",
      title: "Inputan Ada yang kurang",
      text: message,
    });
    return false;
  }
  return true;
}

function showConfirmationDialog(
  title,
  text,
  confirmButtonText,
  cancelButtonText,
  callback
) {
  Swal.fire({
    title: title || "Konfirmasi",
    text: text || "Apakah Anda yakin ingin melanjutkan?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: confirmButtonText || "Ya",
    cancelButtonText: cancelButtonText || "Batal",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed && typeof callback === "function") {
      callback();
    }
  });
}

function showLoadingAlert(options = {}) {
  const defaultOptions = {
    title: "Menyimpan data...",
    text: "Harap tunggu.",
  };

  const finalOptions = { ...defaultOptions, ...options };

  Swal.fire({
    title: finalOptions.title,
    text: finalOptions.text,
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
}

function showAlert(icon, title, text, callback = null) {
  Swal.fire({
    icon: icon,
    title: title,
    text: text,
  }).then(() => {
    if (typeof callback === "function") callback();
  });
}

function defaultSelect2(selector, placeholder, parent = null) {
  $(selector).select2({
    placeholder,
    allowClear: true,
    width: "100%",
    dropdownParent: parent ? $(parent) : undefined,
  });
}

function formatStringNumber(numberInput, locale = "id-ID") {
  if (typeof numberInput !== "string" && typeof numberInput !== "number") {
    console.error("type angka harus berupa string atau number!");
    return numberInput;
  }

  let inputString =
    typeof numberInput === "number" ? numberInput.toString() : numberInput;

  let sanitizedInput = inputString.replace(/[^\d,.-]/g, "");

  sanitizedInput = sanitizedInput.replace(",", ".");

  const number = parseFloat(sanitizedInput);

  if (isNaN(number)) {
    console.error("Input tidak dapat diubah menjadi angka!");
    return input;
  }

  return new Intl.NumberFormat(locale).format(number);
}

function angkaTerbilang(input) {
  if (typeof input !== "string" && typeof input !== "number") {
    console.error("Input harus berupa string atau number!");
    return "Input tidak valid!";
  }

  let inputString = typeof input === "number" ? input.toString() : input;

  inputString = inputString.replace(/[^\d,.-]/g, "");

  let number = parseFloat(inputString.replace(",", "."));
  if (isNaN(number)) {
    console.error("Input tidak dapat diubah menjadi angka!");
    return "Input tidak valid!";
  }

  const satuan = [
    "",
    "satu",
    "dua",
    "tiga",
    "empat",
    "lima",
    "enam",
    "tujuh",
    "delapan",
    "sembilan",
  ];
  const tingkat = ["", "ribu", "juta", "miliar", "triliun", "kuadriliun"];

  function tigaDigitTerbilang(angka) {
    let hasil = "";

    const ratusan = Math.floor(angka / 100);
    const puluhan = Math.floor((angka % 100) / 10);
    const satuanAkhir = angka % 10;

    if (ratusan > 0) {
      hasil += ratusan === 1 ? "seratus " : satuan[ratusan] + " ratus ";
    }

    if (puluhan > 0) {
      if (puluhan === 1) {
        if (satuanAkhir === 0) {
          hasil += "sepuluh ";
        } else if (satuanAkhir === 1) {
          hasil += "sebelas ";
        } else {
          hasil += satuan[satuanAkhir] + " belas ";
        }
      } else {
        hasil += satuan[puluhan] + " puluh ";
      }
    }

    if (puluhan !== 1 && satuanAkhir > 0) {
      hasil += satuan[satuanAkhir] + " ";
    }

    return hasil.trim();
  }

  let hasilTerbilang = "";
  let i = 0;

  while (number > 0) {
    const tigaDigit = number % 1000;
    if (tigaDigit > 0) {
      const bagianTerbilang = tigaDigitTerbilang(tigaDigit);
      hasilTerbilang =
        bagianTerbilang + " " + tingkat[i] + " " + hasilTerbilang;
    }
    number = Math.floor(number / 1000);
    i++;
  }

  return hasilTerbilang.trim() || "nol";
}

function setLocaleIndonesia() {
  moment.defineLocale("id", {
    months:
      "Januari_Februari_Maret_April_Mei_Juni_Juli_Agustus_September_Oktober_November_Desember".split(
        "_"
      ),
    monthsShort: "Jan_Feb_Mar_Apr_Mei_Jun_Jul_Agt_Sep_Okt_Nov_Des".split("_"),
    weekdays: "Minggu_Senin_Selasa_Rabu_Kamis_Jumat_Sabtu".split("_"),
    weekdaysShort: "Min_Sen_Sel_Rab_Kam_Jum_Sab".split("_"),
    weekdaysMin: "Mg_Sn_Sl_Rb_Km_Jm_Sb".split("_"),
    longDateFormat: {
      LT: "HH.mm",
      LTS: "HH.mm.ss",
      L: "DD/MM/YYYY",
      LL: "D MMMM YYYY",
      LLL: "D MMMM YYYY [pukul] HH.mm",
      LLLL: "dddd, D MMMM YYYY [pukul] HH.mm",
    },
    meridiemParse: /pagi|siang|sore|malam/,
    meridiemHour: function (hour, meridiem) {
      if (hour === 12) {
        hour = 0;
      }
      if (meridiem === "pagi") {
        return hour;
      } else if (meridiem === "siang") {
        return hour >= 11 ? hour : hour + 12;
      } else if (meridiem === "sore" || meridiem === "malam") {
        return hour + 12;
      }
    },
    meridiem: function (hours, minutes, isLower) {
      if (hours < 11) {
        return "pagi";
      } else if (hours < 15) {
        return "siang";
      } else if (hours < 19) {
        return "sore";
      } else {
        return "malam";
      }
    },
    calendar: {
      sameDay: "[Hari ini pukul] LT",
      nextDay: "[Besok pukul] LT",
      nextWeek: "dddd [pukul] LT",
      lastDay: "[Kemarin pukul] LT",
      lastWeek: "dddd [lalu pukul] LT",
      sameElse: "L",
    },
    relativeTime: {
      future: "dalam %s",
      past: "%s yang lalu",
      s: "beberapa detik",
      ss: "%d detik",
      m: "semenit",
      mm: "%d menit",
      h: "sejam",
      hh: "%d jam",
      d: "sehari",
      dd: "%d hari",
      M: "sebulan",
      MM: "%d bulan",
      y: "setahun",
      yy: "%d tahun",
    },
    week: {
      dow: 0,
      doy: 6,
    },
  });

  moment.locale("id");
}


window.formatNumberIDR = formatNumberIDR;
window.makeUppercase = makeUppercase;
window.parseRp = parseRp;
window.capitalizeEachWord = capitalizeEachWord;
window.validateInput = validateInput;
window.showConfirmationDialog = showConfirmationDialog;
window.showLoadingAlert = showLoadingAlert;
window.showAlert = showAlert;
window.defaultSelect2 = defaultSelect2;
window.formatStringNumber = formatStringNumber;
window.angkaTerbilang = angkaTerbilang;
window.setLocaleIndonesia = setLocaleIndonesia;