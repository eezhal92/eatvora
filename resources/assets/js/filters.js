const rupiah = function (val) {
  return `Rp. ${val.toLocaleString()}`;
};

module.exports = {
  rupiah,
};