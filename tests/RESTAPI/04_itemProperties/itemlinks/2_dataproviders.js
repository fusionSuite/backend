global.dataProvider = [
  {
    description: 'id not exists',
    value: [57684],
    errorMessageDefault: 'The Default item does not exist',
    errorMessage: 'The Value is an id than does not exist (property Test for itemlinks - ',
  },
  {
    description: 'wrong type',
    value: 57684,
    errorMessageDefault: 'The Default is not valid type',
    errorMessage: 'The Value must be array (property Test for itemlinks - ',
  },
  {
    description: '[\'2\']',
    value: ['2'],
    errorMessageDefault: 'The Default is not valid type',
    errorMessage: 'The Value is not valid type (property Test for itemlinks - ',
  },
  {
    description: '[\'25646546\']',
    value: ['25646546'],
    errorMessageDefault: 'The Default is not valid type',
    errorMessage: 'The Value is not valid type (property Test for itemlinks - ',
  },
  {
    description: '[global.itemId1, 436882]',
    value: [global.itemId1, 436882],
    errorMessageDefault: 'The Default item does not exist',
    errorMessage: 'The Value is an id than does not exist (property Test for itemlinks - ',
  },
  {
    description: '[global.itemId1, null]',
    value: [global.itemId1, null],
    errorMessageDefault: 'The Default item does not exist',
    errorMessage: 'The Value is an id than does not exist (property Test for itemlinks - ',
  },
];

global.dataProviderItemlink = [
  {
    description: 'item id in string',
    value: '123',
    errorMessage: 'The Value is not valid type',
  },
  {
    description: 'boolean',
    value: true,
    errorMessage: 'The Value is not valid type',
  },
  {
    description: 'wrong item id',
    value: 47586,
    errorMessage: 'The Value is an id than does not exist',
  },
  {
    description: 'wrong item id (negative integer)',
    value: -1,
    errorMessage: 'The Value is not valid format',
  },
  {
    description: 'wrong item id: 0',
    value: 0,
    errorMessage: 'The Value is an id than does not exist',
  },
  {
    description: 'wrong type, id into an array',
    value: [2],
    errorMessage: 'The Value is not valid type',
  },
];
