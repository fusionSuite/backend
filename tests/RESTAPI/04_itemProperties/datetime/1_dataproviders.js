global.dataProvider = [
  {
    description: 'bad datetime format',
    value: '2022-05-06 13:54',
    errorMessage: 'The Default is not valid datetime',
  },
  {
    description: 'integer',
    value: 2022,
    errorMessage: 'The Default is not valid type, The Value is not valid datetime',
  },
  {
    description: 'boolean instead string',
    value: true,
    errorMessage: 'The Default is not valid type, The Value is not valid datetime',
  },
  {
    description: 'miss the 0 in the hour',
    value: '2022-05-06 3:64:05',
    errorMessage: 'The Default is not valid datetime',
  },
  {
    description: 'bad minute',
    value: '2022-05-06 13:64:05',
    errorMessage: 'The Default is not valid datetime',
  },
];
