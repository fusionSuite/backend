global.dataProvider = [
  {
    description: 'item id in string',
    value: '123',
    errorMessage: 'The Default is not valid type',
  },
  {
    description: 'boolean',
    value: true,
    errorMessage: 'The Default is not valid type',
  },
  {
    description: 'item id not exists',
    value: 47586,
    errorMessage: 'The Default is an id than does not exist',
  },
  {
    description: 'wrong item id (negative integer)',
    value: -1,
    errorMessage: 'The Default is not valid format',
  },
  {
    description: 'wrong item id: 0',
    value: 0,
    errorMessage: 'The Default is an id than does not exist',
  },
];
