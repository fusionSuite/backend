global.dataProvider = [
  {
    description: 'type id not in array',
    value: 2,
    errorMessage: 'The Default must be array',
  },
  {
    description: 'type id in string',
    value: '2',
    errorMessage: 'The Default must be array',
  },
  {
    description: 'types id (style array) in string',
    value: '[2,3]',
    errorMessage: 'The Default must be array',
  },
  {
    description: 'type id not exists',
    value: [267584],
    errorMessage: 'The Default is an id than does not exist',
  },
  {
    description: 'types id with 1 of the 2 not exists',
    value: [3, 267584],
    errorMessage: 'The Default is an id than does not exist',
  },
];
